<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Activities;
use App\Models\Category;
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

/**
 * Class CategoriesController
 * * Manages the product families for the Helin eCommerce catalog.
 * Handles primary product families and their organizational sequencing (positioning).
 *
 * * @version 1.0.0
 */
#[Title('Gestión de Familias de Productos | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class CategoriesController extends Component
{
    use WithFileUploads;
    use WithPagination;

    /** @var string Display name of the family */
    #[Validate('required|string|max:255')]
    public string $name = '';

    /** @var string|null Slug or internal reference */
    #[Validate('nullable|string|max:255')]
    public ?string $slug = '';

    /** @var string|null Family description */
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

    /** @var bool Featured status */
    #[Validate('boolean')]
    public bool $is_featured = false;

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

    /** @var int|null ID of the family being modified */
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
        CmsAccess::authorize(Module::CATALOG, Submodule::PRODUCT_FAMILIES, __('cms.abort.categories'));
    }

    /**
     * Render the component with paginated and sorted families.
     */
    public function render(): View
    {
        $categories = Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%");
            })
            ->orderBy('order', 'asc')
            ->paginate($this->perPage);

        return view('cms.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Prepare the interface for a new category record.
     */
    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Persist or synchronize category data.
     */
    public function save(): void
    {
        $this->isLoading = true;
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'slug' => $this->slug ?: Str::slug($this->name),
                'description' => $this->description,
                'seo_description' => $this->seo_description,
                'seo_keywords' => $this->seo_keywords,
                'is_active' => $this->is_active,
                'is_featured' => $this->is_featured,
                'banner_title' => $this->banner_title,
                'banner_description' => $this->banner_description,
            ];

            if ($this->image) {
                $filename = time().'_'.$this->image->getClientOriginalName();
                $data['image'] = $this->image->storeAs('categories', $filename, 'public');
            } elseif ($this->editingId) {
                $data['image'] = $this->current_image;
            }

            if ($this->banner_image) {
                $filename = time().'_'.$this->banner_image->getClientOriginalName();
                $data['banner_image'] = $this->banner_image->storeAs('categories/banners', $filename, 'public');
            } elseif ($this->editingId) {
                $data['banner_image'] = $this->current_banner_image;
            }

            if ($this->editingId) {
                $category = Category::findOrFail($this->editingId);
                $category->update($data);

                Activities::saveActivity(__('cms.controllers.categories.activity_updated', ['id' => $category->id]));
                $this->dispatch('toast', message: __('cms.controllers.categories.updated'), type: 'success');
            } else {
                // Insertar al principio: desplazar todas las existentes +1
                Category::query()->increment('order');
                $data['order'] = 1;

                $category = Category::create($data);

                Activities::saveActivity(__('cms.controllers.categories.activity_created', ['id' => $category->id]));
                $this->dispatch('toast', message: __('cms.controllers.categories.created'), type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.categories.process_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Hydrate form with existing category data.
     */
    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->editingId = $id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->seo_description = $category->seo_description;
        $this->seo_keywords = $category->seo_keywords;
        $this->is_active = $category->is_active;
        $this->is_featured = (bool) $category->is_featured;
        $this->banner_title = $category->banner_title;
        $this->banner_description = $category->banner_description;
        $this->current_image = $category->image;
        $this->current_banner_image = $category->banner_image;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Execute category removal after UI confirmation.
     */
    public function confirmDelete(int $id): void
    {
        try {
            $category = Category::findOrFail($id);
            $categoryName = $category->name;
            $category->delete();

            Activities::saveActivity(__('cms.controllers.categories.activity_deleted', ['name' => $categoryName]));
            $this->dispatch('toast', message: __('cms.controllers.categories.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.categories.delete_error'), type: 'error');
        }
    }

    /**
     * Reorder the display sequence of categories via drag & drop data.
     *
     * * @param array $orderedIds Array of IDs in the new order
     */
    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                Category::query()->where('id', $id)->update(['order' => $index + 1]);
            }

            Activities::saveActivity(__('cms.controllers.categories.activity_reordered', ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.categories.order_updated'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.categories.order_error'), type: 'error');
        }
    }

    /**
     * Close form and reset internal state.
     */
    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('close-form');
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => __('cms.validation_attributes.category_name'),
        ];
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'slug', 'description', 'seo_description', 'seo_keywords', 'is_active', 'is_featured', 'banner_title', 'banner_description', 'image', 'current_image', 'banner_image', 'current_banner_image', 'editingId']);
        $this->is_active = true;
        $this->is_featured = false;
        $this->resetValidation();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Compatibility bridge for legacy frontend calls.
     */
    public function getCategoryLists(): array
    {
        return Category::orderBy('order', 'asc')->get()->toArray();
    }
}
