<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\BlogCategory;
use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class BlogCategoriesController
 * 
 * Manages blog categories within the Helin CMS content management system.
 * Handles blog taxonomy organization, visual customization, and display sequencing.
 * Provides reactive interface for blog category lifecycle management with SEO optimization.
 * 
 * Features:
 * - Hierarchical organization support
 * - Visual customization (colors, icons, images)
 * - SEO-friendly URL management
 * - Active/inactive status control
 * - Engagement tracking integration
 * - Bulk operations support
 * 
 * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Blog Category Management | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class BlogCategoriesController extends Component
{
    use WithPagination;

    /** @var string Display name of the blog category */
    #[Validate('required|string|max:255', as: 'nombre de categoría')]
    public string $name = '';

    /** @var string|null SEO-friendly slug for URL generation */
    #[Validate('nullable|string|max:255')]
    public ?string $slug = '';

    /** @var string|null Category description for SEO and display */
    #[Validate('nullable|string|max:1000')]
    public ?string $description = '';

    /** @var string|null Hex color code for visual theme */
    #[Validate('nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/')]
    public ?string $color = '';

    /** @var string|null Icon identifier for UI display */
    #[Validate('nullable|string|max:50')]
    public ?string $icon = '';

    /** @var string|null Image path or URL for category banner */
    #[Validate('nullable|string|max:255')]
    public ?string $image = '';

    /** @var int Numerical order for frontend display */
    #[Validate('required|integer|min:0')]
    public int $order = 0;

    /** @var int|null ID of the blog category being modified */
    public ?int $editingId = null;

    /** @var string Search query for real-time filtering */
    public string $search = '';

    /** @var int Pagination limit */
    public int $perPage = 20;

    /** @var bool Modal visibility state */
    public bool $showForm = false;

    /** @var bool Global loading indicator */
    public bool $isLoading = false;

    /** @var string Custom pagination theme */
    protected string $paginationTheme = 'tailwind';

    /**
     * Component Lifecycle: Authorization Check
     * 
     * Validates user permissions to access blog category management.
     * Only administrators and content managers can access this module.
     * 
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, 'Unauthorized access to Helin Blog Categories module.');
        }
    }

    /**
     * Render the component with paginated and sorted blog categories
     * 
     * Displays blog categories in a tabular format with search capabilities,
     * pagination, and ordering. Includes both active and inactive categories
     * for comprehensive management.
     * 
     * @return View
     */
    public function render(): View
    {
        $blogCategories = BlogCategory::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate($this->perPage);

        return view('cms.blog_categories.index', [
            'blogCategories' => $blogCategories
        ]);
    }

    /**
     * Prepare the interface for a new blog category record
     * 
     * Initializes form fields with default values and calculates the next
     * order position automatically. Opens the modal for data entry.
     * 
     * @return void
     */
    public function create(): void
    {
        $this->resetForm();
        // Auto-assign the next position
        $this->order = BlogCategory::max('order') + 1;
        // Set default color
        $this->color = '#3B82F6';
        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Persist or synchronize blog category data
     * 
     * Handles both creation and update operations with comprehensive validation.
     * Automatically generates slug if not provided. Updates activity log and
     * provides user feedback through toast notifications.
     * 
     * @return void
     */
    public function save(): void
    {
        $this->isLoading = true;
        $this->validate();

        try {
            $data = [
                'name'        => $this->name,
                'slug'        => $this->slug ?: \Illuminate\Support\Str::slug($this->name),
                'description' => $this->description,
                'color'       => $this->color ?: '#3B82F6',
                'icon'        => $this->icon,
                'image'       => $this->image,
                'order'       => $this->order,
            ];

            if ($this->editingId) {
                $blogCategory = BlogCategory::findOrFail($this->editingId);
                $blogCategory->update($data);

                Activities::saveActivity("Blog Category Updated: ID #{$blogCategory->id}");
                $this->dispatch('toast', message: 'Categoría de blog actualizada correctamente', type: 'success');
            } else {
                $blogCategory = BlogCategory::create($data);

                Activities::saveActivity("Blog Category Created: ID #{$blogCategory->id}");
                $this->dispatch('toast', message: 'Categoría de blog creada correctamente', type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al procesar la categoría de blog', type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Hydrate form with existing blog category data
     * 
     * Loads all category properties into the form for editing.
     * Opens the modal and prepares the interface for modification.
     * 
     * @param int $id The blog category identifier
     * @return void
     */
    public function edit(int $id): void
    {
        $blogCategory = BlogCategory::findOrFail($id);

        $this->editingId  = $id;
        $this->name       = $blogCategory->name;
        $this->slug       = $blogCategory->slug;
        $this->description = $blogCategory->description;
        $this->color      = $blogCategory->color;
        $this->icon       = $blogCategory->icon;
        $this->image      = $blogCategory->image;
        $this->order      = $blogCategory->order;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Execute blog category removal after UI confirmation
     * 
     * Permanently deletes a blog category and associated data.
     * Updates activity log and provides user feedback.
     * Handles potential constraint violations gracefully.
     * 
     * @param int $id The blog category identifier
     * @return void
     */
    public function confirmDelete(int $id): void
    {
        try {
            $blogCategory = BlogCategory::findOrFail($id);
            $blogCategoryName = $blogCategory->name;
            $blogCategory->delete();

            Activities::saveActivity("Blog Category Removed: {$blogCategoryName}");
            $this->dispatch('toast', message: 'Categoría de blog eliminada correctamente', type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'No se puede eliminar la categoría. Verifique blogs asociados.', type: 'error');
        }
    }

    /**
     * Reorder the display sequence of blog categories via drag & drop data
     * 
     * Updates the order field for multiple categories in a single operation.
     * Validates the input data and updates activity log for audit trail.
     * 
     * @param array $orderedIds Array of IDs in the new order
     * @return void
     */
    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                BlogCategory::query()->where('id', $id)->update(['order' => $index]);
            }

            Activities::saveActivity("Blog Categories Reordered by User ID #" . Auth::id());
            $this->dispatch('toast', message: 'Orden de categorías actualizado correctamente', type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al reordenar las categorías de blog', type: 'error');
        }
    }

    /**
     * Close form and reset internal state
     * 
     * Clears all form data, hides the modal, and resets validation state.
     * Dispatches event to notify frontend components of state change.
     * 
     * @return void
     */
    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('close-form');
    }

    /**
     * Clear all reactive form properties
     * 
     * Resets all form fields to their default values and clears validation
     * errors. Used during form initialization and cleanup operations.
     * 
     * @return void
     */
    private function resetForm(): void
    {
        $this->reset([
            'name', 'slug', 'description', 'color', 'icon', 'image', 
            'order', 'editingId'
        ]);
        $this->resetValidation();
    }

    /**
     * Lifecycle listener: Pagination reset on search
     * 
     * Automatically resets pagination to first page when search query changes.
     * Ensures consistent user experience during search operations.
     * 
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Compatibility bridge for legacy frontend calls
     * 
     * Provides backward compatibility for existing frontend components
     * that may rely on the old method naming convention.
     * 
     * @return array
     */
    public function getBlogCategoryLists(): array
    {
        return BlogCategory::orderBy('order', 'asc')
                          ->orderBy('name', 'asc')
                          ->get()
                          ->toArray();
    }
}
