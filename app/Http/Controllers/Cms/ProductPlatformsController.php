<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\ProductPlatform;
use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class ProductPlatformsController
 * * Manages the product platforms for the Helin eCommerce catalog.
 * Handles primary product platforms and their organizational sequencing (positioning).
 * * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Gestión de Plataforma de Productos | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class ProductPlatformsController extends Component
{

    use WithPagination;

    /** @var string Display name of the platform */
    #[Validate('required|string|max:255')]
    public string $name = '';

    /** @var string|null Slug or internal reference */
    #[Validate('nullable|string|max:255')]
    public ?string $slug = '';

    /** @var string|null Platform description */
    #[Validate('nullable|string|max:1000')]
    public ?string $description = '';

    /** @var string|null SEO description for meta tags */
    #[Validate('nullable|string|max:1000')]
    public ?string $seo_description = '';

    /** @var int|null ID of the platform being modified */
    public ?int $editingId = null;

    /** @var string Search query for real-time filtering */
    public string $search = '';

    /** @var int Pagination limit */
    public int $perPage = 20;

    /** @var bool Modal visibility state */
    public bool $showForm = false;

    /** @var bool Active status */
    public bool $is_active = true;

    /** @var bool Global loading indicator */
    public bool $isLoading = false;

    /** @var string Custom pagination theme */
    protected string $paginationTheme = 'tailwind';

    /**
     * Component Lifecycle: Authorization Check.
     */
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, __('cms.abort.product_platforms'));
        }
    }

    /**
     * Render the component with paginated and sorted platforms.
     */
    public function render(): View
    {
        $platforms = ProductPlatform::query()
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%");
                })
                ->orderBy('order', 'asc')
                ->paginate($this->perPage);

        return view('cms.product-platforms.index', [
            'platforms' => $platforms,
        ]);
    }

    /**
     * Open form for creating a new platform.
     */
    public function create(): void
    {
        $this->reset();
        $this->showForm = true;
    }

    /**
     * Open form for editing an existing platform.
     */
    public function edit(int $id): void
    {
        $platform = ProductPlatform::findOrFail($id);
        $this->editingId = $id;
        $this->name = $platform->name;
        $this->slug = $platform->slug;
        $this->description = $platform->description;
        $this->seo_description = $platform->seo_description;
        $this->is_active = $platform->is_active;
        $this->showForm = true;
    }

    /**
     * Save a new platform to the database.
     */
    public function save(): void
    {
        $this->validate();

        try {
            $platform = ProductPlatform::create([
                'name' => $this->name,
                'slug' => $this->slug ?: str()->slug($this->name),
                'description' => $this->description,
                'seo_description' => $this->seo_description,
                'is_active' => $this->is_active,
                'order' => ProductPlatform::max('order') + 1,
            ]);

            Activities::saveActivity(__('cms.controllers.product_platforms.activity_created', ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.product_platforms.created'), type: 'success');
            $this->reset();
            $this->showForm = false;
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.product_platforms.create_error'), type: 'error');
        }
    }

    /**
     * Update an existing platform.
     */
    public function update(): void
    {
        $this->validate();

        try {
            $platform = ProductPlatform::findOrFail($this->editingId);
            $platform->update([
                'name' => $this->name,
                'slug' => $this->slug ?: str()->slug($this->name),
                'description' => $this->description,
                'seo_description' => $this->seo_description,
                'is_active' => $this->is_active,
            ]);

            Activities::saveActivity(__('cms.controllers.product_platforms.activity_updated', ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.product_platforms.updated'), type: 'success');
            $this->reset();
            $this->showForm = false;
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.product_platforms.update_error'), type: 'error');
        }
    }

    /**
     * Delete a platform from the database.
     */
    public function delete(int $id): void
    {
        try {
            $platform = ProductPlatform::findOrFail($id);
            $platform->delete();

            Activities::saveActivity(__('cms.controllers.product_platforms.activity_deleted', ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.product_platforms.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.product_platforms.delete_error'), type: 'error');
        }
    }

    /**
     * Toggle the active status of a platform.
     */
    public function toggleActive(int $id): void
    {
        try {
            $platform = ProductPlatform::findOrFail($id);
            $platform->update(['is_active' => !$platform->is_active]);

            $status = $platform->is_active ? 'activated' : 'deactivated';
            Activities::saveActivity(__('cms.controllers.product_platforms.activity_' . $status, ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.product_platforms.' . $status), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.product_platforms.status_error'), type: 'error');
        }
    }

    /**
     * Update the order of platforms.
     */
    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                ProductPlatform::query()->where('id', $id)->update(['order' => $index + 1]);
            }
            Activities::saveActivity(__('cms.controllers.product_platforms.activity_reordered', ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.product_platforms.order_updated'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.product_platforms.order_error'), type: 'error');
        }
    }


    /**
     * Close the form modal.
     */
    public function closeForm(): void
    {
        $this->showForm = false;
        $this->reset();
    }

    /**
     * Reset pagination when search is updated.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when per page is updated.
     */
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }
}
