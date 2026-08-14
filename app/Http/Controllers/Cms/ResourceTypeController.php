<?php

namespace App\Http\Controllers\Cms;

use App\Models\Module;
use App\Models\ResourceType;
use App\Models\Submodule;
use App\Utils\CmsAccess;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Gestión de Tipos de Recursos | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class ResourceTypeController extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $showForm = false;

    public $editingId = null;

    // Form fields
    public $name;

    public $description;

    public $is_active = true;

    public $position = 0;

    // File upload
    public $image;

    public ?string $current_image = null;

    public $banner_title;

    public $banner_description;

    public $banner_image;

    public ?string $current_banner_image = null;

    // Filters
    public $search = '';

    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'is_active' => 'boolean',
        'position' => 'integer|min:0',
        'image' => 'nullable|image|max:2048',
        'banner_image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        CmsAccess::authorize(Module::CONTENT, Submodule::RESOURCE_TYPES, __('cms.abort.resource_types'));
        $this->resetFilters();
    }

    public function render()
    {
        $query = ResourceType::query();

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        // Order by position
        $resourceTypes = $query->orderBy('position')->orderBy('updated_at', 'desc')
            ->paginate($this->perPage);

        return view('cms.resource-types.index', compact('resourceTypes'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function edit($id)
    {
        $resourceType = ResourceType::findOrFail($id);

        $this->editingId = $id;
        $this->name = $resourceType->name;
        $this->description = $resourceType->description;
        $this->is_active = $resourceType->is_active;
        $this->position = $resourceType->position;
        $this->banner_title = $resourceType->banner_title;
        $this->banner_description = $resourceType->banner_description;
        $this->current_image = $resourceType->image;
        $this->current_banner_image = $resourceType->banner_image;

        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'banner_title' => $this->banner_title,
            'banner_description' => $this->banner_description,
        ];

        if ($this->image) {
            $filename = time().'_'.$this->image->getClientOriginalName();
            $data['image'] = $this->image->storeAs('resource-types', $filename, 'public');
        } elseif ($this->editingId) {
            $data['image'] = $this->current_image;
        }

        if ($this->banner_image) {
            $filename = time().'_'.$this->banner_image->getClientOriginalName();
            $data['banner_image'] = $this->banner_image->storeAs('resource-types/banners', $filename, 'public');
        } elseif ($this->editingId) {
            $data['banner_image'] = $this->current_banner_image;
        }

        if ($this->editingId) {
            $resourceType = ResourceType::findOrFail($this->editingId);
            $resourceType->update($data);
            $this->dispatch('toast', message: 'Tipo de recurso actualizado exitosamente', type: 'success');
        } else {
            ResourceType::create($data);
            $this->dispatch('toast', message: 'Tipo de recurso creado exitosamente', type: 'success');
        }

        $this->cancel();
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
        $this->editingId = null;
    }

    public function confirmDelete($id)
    {
        $resourceType = ResourceType::findOrFail($id);

        // Check if there are associated resources
        if ($resourceType->resources()->count() > 0) {
            $this->dispatch('toast', message: 'No se puede eliminar el tipo de recurso porque tiene recursos asociados', type: 'error');

            return;
        }

        $resourceType->delete();
        $this->dispatch('toast', message: 'Tipo de recurso eliminado exitosamente', type: 'success');
    }

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            ResourceType::where('id', $id)->update(['position' => $index]);
        }
        $this->dispatch('toast', message: 'Orden actualizado exitosamente', type: 'success');
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'description',
            'is_active', 'image', 'current_image',
            'banner_title', 'banner_description', 'banner_image', 'current_banner_image',
        ]);

        $this->is_active = true;
    }

    public function resetFilters()
    {
        $this->reset(['search']);
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
