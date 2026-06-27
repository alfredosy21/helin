<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Menus;
use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class MenuController
 *
 * Manages website navigation menu structure including header, footer, and sidebar menus.
 * Handles hierarchical menu organization with parent-child relationships.
 * Provides interface for managing menu items, URLs, icons, and display ordering.
 *
 * Features:
 * - Menu item management (create, edit, delete)
 * - Hierarchical menu structure (parent-child)
 * - Multiple menu types (header, footer, sidebar)
 * - Icon and image support
 * - URL validation and management
 * - Drag & drop ordering
 * - Activity logging
 * - Role-based access control
 *
 * @version 1.0.0
 * @package App\Http\Livewire\Cms
 */
#[Title('Gestión de Menú del Sitio | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class MenuController extends Component {

    use WithPagination, WithFileUploads;

    // Menu Properties
    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string|max:500')]
    public ?string $url = '';

    #[Validate('required|integer|in:1,2,3')]
    public int $type = 1;

    #[Validate('nullable|integer|min:0')]
    public ?int $position = null;

    public bool $status = true;
    public bool $target_blank = false;

    #[Validate('nullable|string|max:500')]
    public ?string $description = '';

    #[Validate('nullable|string|max:100')]
    public ?string $icon = '';

    public ?int $parent_id = null;

    // File Upload
    public $image;
    public ?string $current_image = null;

    // UI State
    public ?int $editingId = null;
    public string $search = '';
    public int $perPage = 20;
    public bool $showForm = false;
    public bool $isLoading = false;
    protected string $paginationTheme = 'tailwind';

    // Filters
    public int $filterType = 0; // 0 = All, 1 = Header, 2 = Footer, 3 = Sidebar

    /**
     * Component Lifecycle: Authorization Check
     */
    public function mount(): void {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, 'No tienes permisos para acceder a esta sección');
        }
    }

    /**
     * Render the component with menu items
     */
    public function render(): View {
        $query = Menus::query()
            ->with(['parent', 'children']);

        // Apply type filter
        if ($this->filterType > 0) {
            $query->where('type', $this->filterType);
        }

        // Apply search
        $query->when($this->search, function ($q) {
            $q->where('title', 'like', "%{$this->search}%")
              ->orWhere('url', 'like', "%{$this->search}%")
              ->orWhere('description', 'like', "%{$this->search}%");
        });

        $menus = $query->orderBy('type')
            ->orderBy('parent_id')
            ->orderBy('position', 'asc')
            ->paginate($this->perPage);

        // Get available parent menus
        $availableParents = Menus::whereNull('parent_id')
            ->when($this->editingId, function ($q) {
                $q->where('id', '!=', $this->editingId);
            })
            ->orderBy('type')
            ->orderBy('position')
            ->get();

        return view('cms.menu.index', [
            'menus' => $menus,
            'availableParents' => $availableParents,
            'menuTypes' => Menus::getTypes()
        ]);
    }

    /**
     * Prepare interface for new menu item
     */
    public function create(): void {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Save menu data
     */
    public function save(): void {
        $this->isLoading = true;
        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'url' => $this->url,
                'type' => $this->type,
                'status' => $this->status,
                'target_blank' => $this->target_blank,
                'description' => $this->description,
                'icon' => $this->icon,
                'parent_id' => $this->parent_id,
            ];

            // Handle position
            if ($this->position) {
                $data['position'] = $this->position;
            } else {
                $maxPosition = Menus::where('type', $this->type)
                    ->where('parent_id', $this->parent_id)
                    ->max('position') ?? 0;
                $data['position'] = $maxPosition + 1;
            }

            // Handle image upload
            if ($this->image) {
                $path = $this->image->store('menu-images', 'public');
                $data['image'] = $path;
            } elseif ($this->editingId) {
                $data['image'] = $this->current_image;
            }

            if ($this->editingId) {
                $menu = Menus::findOrFail($this->editingId);
                $menu->update($data);
                Activities::saveActivity("Menú actualizado: {$menu->title}");
                $this->dispatch('toast', message: 'Menú actualizado correctamente', type: 'success');
            } else {
                $menu = Menus::create($data);
                Activities::saveActivity("Menú creado: {$menu->title}");
                $this->dispatch('toast', message: 'Menú creado correctamente', type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al procesar el menú', type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Edit existing menu item
     */
    public function edit(int $id): void {
        $menu = Menus::findOrFail($id);
        $this->editingId = $id;
        $this->title = $menu->title;
        $this->url = $menu->url;
        $this->type = $menu->type;
        $this->position = $menu->position;
        $this->status = $menu->status;
        $this->target_blank = $menu->target_blank;
        $this->description = $menu->description;
        $this->icon = $menu->icon;
        $this->parent_id = $menu->parent_id;
        $this->current_image = $menu->image;
        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Delete menu item
     */
    public function delete(int $id): void {
        try {
            $menu = Menus::findOrFail($id);
            $menuTitle = $menu->title;
            $menu->delete();
            Activities::saveActivity("Menú eliminado: {$menuTitle}");
            $this->dispatch('toast', message: 'Menú eliminado correctamente', type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al eliminar el menú', type: 'error');
        }
    }

    /**
     * Update menu order
     */
    public function updateOrder(array $orderedIds): void {
        try {
            foreach ($orderedIds as $index => $id) {
                Menus::query()->where('id', $id)->update(['position' => $index + 1]);
            }
            Activities::saveActivity("Orden de menús actualizado por usuario: " . Auth::id());
            $this->dispatch('toast', message: 'Orden actualizado correctamente', type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al actualizar orden', type: 'error');
        }
    }

    /**
     * Toggle menu status
     */
    public function toggleStatus(int $id): void {
        try {
            $menu = Menus::findOrFail($id);
            $menu->status = !$menu->status;
            $menu->save();
            $statusText = $menu->status ? 'activado' : 'desactivado';
            Activities::saveActivity("Menú {$statusText}: {$menu->title}");
            $this->dispatch('toast', message: "Menú {$statusText} correctamente", type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al cambiar estado', type: 'error');
        }
    }

    /**
     * Close form and reset state
     */
    public function cancel(): void {
        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('close-form');
    }

    /**
     * Reset form fields
     */
    private function resetForm(): void {
        $this->reset([
            'title', 'url', 'type', 'position', 'status', 'target_blank',
            'description', 'icon', 'parent_id', 'image', 'current_image', 'editingId'
        ]);
        $this->status = true;
        $this->type = 1;
        $this->resetValidation();
    }

    /**
     * Get menu type label
     */
    public function getTypeLabel(int $type): string {
        $types = Menus::getTypes();
        return $types[$type] ?? 'Desconocido';
    }

    /**
     * Get parent menu label
     */
    public function getParentLabel(?int $parentId): string {
        if (!$parentId) return 'Ninguno';
        $parent = Menus::find($parentId);
        return $parent ? $parent->title : 'Ninguno';
    }

    /**
     * Reset pagination on search
     */
    public function updatedSearch(): void {
        $this->resetPage();
    }

    /**
     * Reset pagination on filter change
     */
    public function updatedFilterType(): void {
        $this->resetPage();
    }

    /**
     * Validation rules
     */
    protected function rules(): array {
        return [
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'type' => 'required|integer|in:1,2,3',
            'position' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'target_blank' => 'boolean',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:menus,id',
            'image' => 'nullable|image|max:2048'
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array {
        return [
            'title.required' => 'El título es obligatorio',
            'title.max' => 'El título no puede exceder 255 caracteres',
            'url.max' => 'La URL no puede exceder 500 caracteres',
            'type.required' => 'El tipo de menú es obligatorio',
            'type.in' => 'El tipo de menú seleccionado no es válido',
            'parent_id.exists' => 'El menú padre seleccionado no existe',
            'image.image' => 'El archivo debe ser una imagen',
            'image.max' => 'La imagen no puede exceder 2MB'
        ];
    }
}
