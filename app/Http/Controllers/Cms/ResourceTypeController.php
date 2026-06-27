<?php

namespace App\Http\Controllers\Cms;

use App\Models\ResourceType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class ResourceTypeController extends Component
{
    use WithPagination;

    public $showForm = false;
    public $editingId = null;

    // Form fields
    public $name;
    public $slug;
    public $description;
    public $icon;
    public $color;
    public $is_active = true;
    public $position = 0;
    public $config;

    // Filters
    public $search = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:resource_types,slug',
        'description' => 'nullable|string|max:1000',
        'icon' => 'nullable|string|max:100',
        'color' => 'nullable|string|max:20',
        'is_active' => 'boolean',
        'position' => 'integer|min:0',
        'config' => 'nullable|string|max:2000',
    ];

    public function mount()
    {
        $this->resetFilters();
    }

    public function render()
    {
        $query = ResourceType::query();

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
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
        $this->slug = $resourceType->slug;
        $this->description = $resourceType->description;
        $this->icon = $resourceType->icon;
        $this->color = $resourceType->color;
        $this->is_active = $resourceType->is_active;
        $this->position = $resourceType->position;
        $this->config = $resourceType->config ? json_encode($resourceType->config) : '';

        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        // Custom validation for unique slug (except when editing)
        if ($this->editingId) {
            $this->rules['slug'] = 'required|string|max:255|unique:resource_types,slug,' . $this->editingId;
        }

        $this->validate($this->rules);

        // Process config JSON
        $configArray = null;
        if ($this->config) {
            try {
                $configArray = json_decode($this->config, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->addError('config', 'El formato JSON es inválido');
                    return;
                }
            } catch (\Exception $e) {
                $this->addError('config', 'El formato JSON es inválido');
                return;
            }
        }

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'is_active' => $this->is_active,
            'position' => $this->position,
            'config' => $configArray,
        ];

        if ($this->editingId) {
            $resourceType = ResourceType::findOrFail($this->editingId);
            $resourceType->update($data);
            $this->dispatch('showToast', 'Tipo de recurso actualizado exitosamente', 'success');
        } else {
            ResourceType::create($data);
            $this->dispatch('showToast', 'Tipo de recurso creado exitosamente', 'success');
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
            $this->dispatch('showToast', 'No se puede eliminar el tipo de recurso porque tiene recursos asociados', 'error');
            return;
        }

        $resourceType->delete();
        $this->dispatch('showToast', 'Tipo de recurso eliminado exitosamente', 'success');
    }

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            ResourceType::where('id', $id)->update(['position' => $index]);
        }
        $this->dispatch('showToast', 'Orden actualizado exitosamente', 'success');
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'slug', 'description', 'icon', 'color',
            'is_active', 'position', 'config'
        ]);

        $this->is_active = true;
        $this->position = 0;
    }

    public function resetFilters()
    {
        $this->reset(['search']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }
}
