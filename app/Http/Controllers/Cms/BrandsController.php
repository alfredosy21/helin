<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Activities;
use App\Models\Brand;
use App\Models\Module;
use App\Models\Submodule;
use App\Utils\CmsAccess;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class BrandsController
 *
 * Manages product manufacturers and their associated commercial identities
 * within the Helin CMS content management system. Handles brand lifecycle
 * management with visual customization and display sequencing.
 *
 * Features:
 * - Commercial brand identity management
 * - Logo and visual asset management
 * - Display ordering and sequencing
 * - Bulk operations support
 * - Activity logging and audit trail
 * - Role-based access control
 * - Product association tracking
 *
 * @version 1.0.0
 */
#[Title('Gestión de Marcas | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class BrandsController extends Component
{
    use WithFileUploads;
    use WithPagination;

    /** @var string Commercial name of the brand */
    #[Validate('required|string|max:255')]
    public string $name = '';

    /** @var string|null Brand description */
    #[Validate('nullable|string|max:1000')]
    public ?string $description = '';

    /** @var string|null SEO description for meta tags */
    #[Validate('nullable|string|max:1000')]
    public ?string $seo_description = '';

    /** @var string|null SEO keywords for meta tags */
    #[Validate('nullable|string|max:500')]
    public ?string $seo_keywords = '';

    /** @var mixed|null Uploaded image file instance */
    public $image;

    /** @var string|null Existing image path from storage */
    public ?string $current_image = null;

    /** @var string|null Banner title */
    #[Validate('nullable|string|max:255')]
    public ?string $banner_title = '';

    /** @var string|null Banner description */
    #[Validate('nullable|string|max:1000')]
    public ?string $banner_description = '';

    /** @var mixed|null Uploaded banner image file instance */
    public $banner_image;

    /** @var string|null Existing banner image path from storage */
    public ?string $current_banner_image = null;

    /** @var int|null ID of the brand being modified */
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
     * Validates user permissions to access brand management.
     * Only administrators and content managers can access this module.
     *
     * @throws AccessDeniedHttpException
     */
    public function mount(): void
    {
        CmsAccess::authorize(Module::CATALOG, Submodule::PRODUCT_BRANDS, __('cms.abort.brands'));
    }

    /**
     * Render the component with paginated and sorted brands
     *
     * Displays commercial brands in a tabular format with search capabilities,
     * pagination, and ordering. Includes both active and inactive brands
     * for comprehensive management.
     */
    public function render(): View
    {
        $brands = Brand::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate($this->perPage);

        return view('cms.brands.index', [
            'brands' => $brands,
        ]);
    }

    /**
     * Prepare the interface for a new brand record
     *
     * Initializes form fields with default values and calculates the next
     * order position automatically. Opens the modal for data entry.
     */
    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Persist or synchronize brand data
     *
     * Handles both creation and update operations with comprehensive validation.
     * Updates activity log and provides user feedback through toast notifications.
     */
    public function save(): void
    {
        $this->isLoading = true;
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'seo_description' => $this->seo_description,
                'seo_keywords' => $this->seo_keywords,
                'is_active' => $this->is_active,
                'banner_title' => $this->banner_title,
                'banner_description' => $this->banner_description,
            ];

            if ($this->image) {
                $filename = time().'_'.$this->image->getClientOriginalName();
                $data['image'] = $this->image->storeAs('brands', $filename, 'public');
            } elseif ($this->editingId) {
                $data['image'] = $this->current_image;
            }

            if ($this->banner_image) {
                $filename = time().'_'.$this->banner_image->getClientOriginalName();
                $data['banner_image'] = $this->banner_image->storeAs('brands/banners', $filename, 'public');
            } elseif ($this->editingId) {
                $data['banner_image'] = $this->current_banner_image;
            }

            if ($this->editingId) {
                $brand = Brand::findOrFail($this->editingId);
                $brand->update($data);

                Activities::saveActivity(__('cms.controllers.brands.activity_updated', ['id' => $brand->id]));
                $this->dispatch('toast', message: __('cms.controllers.brands.updated'), type: 'success');
            } else {
                Brand::query()->increment('order');
                $data['order'] = 1;

                $brand = Brand::create($data);

                Activities::saveActivity(__('cms.controllers.brands.activity_created', ['id' => $brand->id]));
                $this->dispatch('toast', message: __('cms.controllers.brands.created'), type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.brands.process_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Hydrate form with existing brand data
     *
     * Loads all brand properties into the form for editing.
     * Opens the modal and prepares the interface for modification.
     *
     * @param  int  $id  The brand identifier
     */
    public function edit(int $id): void
    {
        $brand = Brand::findOrFail($id);

        $this->editingId = $id;
        $this->name = $brand->name;
        $this->description = $brand->description;
        $this->seo_description = $brand->seo_description;
        $this->seo_keywords = $brand->seo_keywords;
        $this->is_active = $brand->is_active;
        $this->banner_title = $brand->banner_title;
        $this->banner_description = $brand->banner_description;
        $this->current_image = $brand->image;
        $this->current_banner_image = $brand->banner_image;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Execute brand removal after UI confirmation
     *
     * Permanently deletes a commercial brand and associated data.
     * Updates activity log and provides user feedback.
     * Handles potential constraint violations gracefully.
     *
     * @param  int  $id  The brand identifier
     */
    public function confirmDelete(int $id): void
    {
        try {
            $brand = Brand::findOrFail($id);
            $brandName = $brand->name;
            $brand->delete();

            Activities::saveActivity(__('cms.controllers.brands.activity_deleted', ['name' => $brandName]));
            $this->dispatch('toast', message: __('cms.controllers.brands.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.brands.delete_error'), type: 'error');
        }
    }

    /**
     * Reorder the display sequence of brands via drag & drop data
     *
     * Updates the order field for multiple brands in a single operation.
     * Validates the input data and updates activity log for audit trail.
     *
     * @param  array  $orderedIds  Array of IDs in the new order
     */
    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                Brand::query()->where('id', $id)->update(['order' => $index + 1]);
            }

            Activities::saveActivity(__('cms.controllers.brands.activity_reordered', ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.brands.order_updated'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.brands.order_error'), type: 'error');
        }
    }

    /**
     * Close form and reset internal state
     *
     * Clears all form data, hides the modal, and resets validation state.
     * Dispatches event to notify frontend components of state change.
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
            'name' => __('cms.validation_attributes.brand_name'),
        ];
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'description', 'seo_description', 'seo_keywords', 'is_active', 'banner_title', 'banner_description', 'image', 'current_image', 'banner_image', 'current_banner_image', 'editingId']);
        $this->is_active = true;
        $this->resetValidation();
    }

    /**
     * Lifecycle listener: Pagination reset on search
     *
     * Automatically resets pagination to first page when search query changes.
     * Ensures consistent user experience during search operations.
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
     */
    public function getBrandLists(): array
    {
        return Brand::orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();
    }
}
