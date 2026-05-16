<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Category;
use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class CategoriesController
 * * Manages the product taxonomy for the Helin eCommerce catalog.
 * Handles primary categories and their organizational sequencing (positioning).
 * * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Category Management | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class CategoriesController extends Component
{
    use WithPagination;

    /** @var string Display name of the category */
    #[Validate('required|string|max:255', as: 'nombre de categoría')]
    public string $name = '';

    /** @var string|null Slug or internal reference */
    #[Validate('nullable|string|max:255')]
    public ?string $slug = '';

    /** @var int Numerical order for frontend display */
    #[Validate('required|integer|min:0')]
    public int $order = 0;

    /** @var int|null ID of the category being modified */
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
     * Component Lifecycle: Authorization Check.
     */
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, 'Unauthorized access to Helin Taxonomy modules.');
        }
    }

    /**
     * Render the component with paginated and sorted categories.
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
            'categories' => $categories
        ]);
    }

    /**
     * Prepare the interface for a new category record.
     */
    public function create(): void
    {
        $this->resetForm();
        // Auto-assign the next position
        $this->order = Category::max('order') + 1;
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
                'name'     => $this->name,
                'slug'     => $this->slug ?: \Illuminate\Support\Str::slug($this->name),
                'order' => $this->order,
            ];

            if ($this->editingId) {
                $category = Category::findOrFail($this->editingId);
                $category->update($data);

                Activities::saveActivity("Taxonomía actualizada: Categoría ID #{$category->id}");
                $this->dispatch('toast', message: 'Categoría actualizada correctamente', type: 'success');
            } else {
                $category = Category::create($data);

                Activities::saveActivity("Taxonomía creada: Categoría ID #{$category->id}");
                $this->dispatch('toast', message: 'Categoría creada correctamente', type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al procesar la categoría', type: 'error');
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
        $this->name      = $category->name;
        $this->slug      = $category->slug;
        $this->order      = $category->order;

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

            Activities::saveActivity("Elemento de taxonomía eliminado: {$categoryName}");
            $this->dispatch('toast', message: 'Categoría eliminada correctamente', type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'No se puede eliminar la categoría. Verifique productos asociados.', type: 'error');
        }
    }

    /**
     * Reorder the display sequence of categories via drag & drop data.
     * * @param array $orderedIds Array of IDs in the new order
     */
    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                Category::query()->where('id', $id)->update(['order' => $index]);
            }

            Activities::saveActivity("Taxonomía reordenada por Usuario ID #" . Auth::id());
            $this->dispatch('toast', message: 'Orden actualizado correctamente', type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al reordenar las categorías', type: 'error');
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

    private function resetForm(): void
    {
        $this->reset(['name', 'slug', 'order', 'editingId']);
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
