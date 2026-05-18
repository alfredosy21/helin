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
#[Title('Gestión de Categorías del Blog | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class BlogCategoriesController extends Component
{
    use WithPagination;

    /** @var string Display name of the blog category */
    #[Validate('required|string|max:255')]
    public string $name = '';

    /** @var string|null SEO-friendly slug for URL generation */
    #[Validate('nullable|string|max:255')]
    public ?string $slug = '';

    /** @var string|null Category description for SEO and display */
    #[Validate('nullable|string|max:1000')]
    public ?string $description = '';

    /** @var string|null SEO description for meta tags */
    #[Validate('nullable|string|max:1000')]
    public ?string $seo_description = '';

    /** @var int|null ID of the blog category being modified */
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
            abort(403, __('cms.abort.blog_categories'));
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
                'description'     => $this->description,
                'seo_description' => $this->seo_description,
                'is_active'   => $this->is_active,
            ];

            if ($this->editingId) {
                $blogCategory = BlogCategory::findOrFail($this->editingId);
                $blogCategory->update($data);

                Activities::saveActivity(__('cms.controllers.blog_categories.activity_updated', ['id' => $blogCategory->id]));
                $this->dispatch('toast', message: __('cms.controllers.blog_categories.updated'), type: 'success');
            } else {
                BlogCategory::query()->increment('order');
                $data['order'] = 1;

                $blogCategory = BlogCategory::create($data);

                Activities::saveActivity(__('cms.controllers.blog_categories.activity_created', ['id' => $blogCategory->id]));
                $this->dispatch('toast', message: __('cms.controllers.blog_categories.created'), type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.blog_categories.process_error'), type: 'error');
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
        $this->description     = $blogCategory->description;
        $this->seo_description  = $blogCategory->seo_description;
        $this->is_active  = $blogCategory->is_active;
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

            Activities::saveActivity(__('cms.controllers.blog_categories.activity_deleted', ['name' => $blogCategoryName]));
            $this->dispatch('toast', message: __('cms.controllers.blog_categories.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.blog_categories.delete_error'), type: 'error');
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
                BlogCategory::query()->where('id', $id)->update(['order' => $index + 1]);
            }

            Activities::saveActivity(__('cms.controllers.blog_categories.activity_reordered', ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.blog_categories.order_updated'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.blog_categories.order_error'), type: 'error');
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
    protected function validationAttributes(): array
    {
        return [
            'name' => __('cms.validation_attributes.category_name'),
        ];
    }

    private function resetForm(): void
    {
        $this->reset([
            'name', 'slug', 'description', 'seo_description', 'is_active',
            'editingId'
        ]);
        $this->is_active = true;
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
