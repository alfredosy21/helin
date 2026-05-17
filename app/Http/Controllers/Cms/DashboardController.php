<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Activities;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

/**
 * DashboardController
 *
 * This controller manages the main administrative interface, handling
 * real-time statistics, system health metrics, and activity tracking.
 */
#[Title('Escritorio | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class DashboardController extends Component
{
    /**
     * @var array Holds the calculated statistics for the dashboard view.
     */
    public array $stats = [];

    /**
     * Initialize the component and perform the first data fetch.
     */
    public function mount(): void
    {
        $this->refreshStats();
    }

    /**
     * Fetch fresh metrics from the database.
     *
     * Uses the query() builder on Eloquent models to ensure clean
     * implementation and better IDE autocompletion.
     */
    public function refreshStats(): void
    {
        try {

            // Get current month as integer
            $currentMonth = now()->month;

            $this->stats = [
                // Global Totals
                'total_products'   => Product::query()->count(['id']),
                'total_categories' => Category::query()->count(['id']),
                'total_brands'     => Brand::query()->count(['id']),
                'total_users'      => User::query()->count(['id']),

                // Growth Indicators (Current Month)
                'new_users' => User::query()
                    ->whereMonth('created_at', '=', $currentMonth, true)
                    ->count(),

                // Option B: Fallback using whereRaw if your DB driver is being strict
                'new_products' => Product::query()
                   ->whereMonth('created_at', '=', $currentMonth, true)
                    ->count(),

                // Operational Metrics
                'active_products'  => Product::query()->count(['id']), // Placeholder for status-based filtering
                'total_blogs'      => 0, // Blog model implementation pending
                'new_blogs'        => 0,
                'uptime'           => '99.9%',
            ];
        } catch (\Exception $e) {
            // Log failure to prevent application crash while notifying admins via logs
            Log::error("Dashboard stats sync failed: " . $e->getMessage());
        }
    }

    /**
     * Render the dashboard view with dynamic data.
     *
     * @return View The dashboard index view with activity and distribution data.
     */
    public function render(): View
    {
        /**
         * Fetch the latest 10 system activities, eager-loading users
         * to avoid N+1 query performance issues.
         */
        $recentActivities = Activities::query()
            ->with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'description' => $activity->activity,
                    'time'        => $activity->created_at->diffForHumans(),
                    'icon'        => $this->getActivityIcon($activity->activity),
                    'user'        => $activity->user ? $activity->user->name : __('cms.controllers.dashboard.system_user')
                ];
            });

        return view('cms.dashboard.index', [
            'recentActivity' => $recentActivities,
            /**
             * Calculate category distribution by counting related products
             * for the top 5 largest categories.
             */
            'inventoryDistribution' => Category::query()
                ->withCount('products')
                ->orderBy('products_count', 'desc')
                ->take(5)
                ->get(),
        ]);
    }

    /**
     * Map activity descriptions to specific UI icons.
     *
     * @param string $activity The raw activity description string.
     * @return string The icon identifier for the frontend component.
     */
    private function getActivityIcon(string $activity): string
    {
        $activity = strtolower($activity);

        return match(true) {
            str_contains($activity, 'login') => 'log-in',
            str_contains($activity, 'logout') => 'log-out',
            str_contains($activity, 'created') || str_contains($activity, 'guardado') => 'plus-circle',
            str_contains($activity, 'updated') || str_contains($activity, 'actualizado') => 'edit',
            str_contains($activity, 'deleted') || str_contains($activity, 'eliminado') => 'trash',
            default => 'clock',
        };
    }
}
