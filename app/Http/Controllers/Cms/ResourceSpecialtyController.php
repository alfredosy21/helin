<?php

namespace App\Http\Controllers\Cms;

use App\Models\ResourceSpecialty;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Title('Gestión de Especialidades de Recursos | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class ResourceSpecialtyController extends Component
{
    use WithPagination;

    public $showForm = false;
    public $editingId = null;

    // Form fields
    public $name;
    public $description;
    public $is_active = true;
    public $position = 0;

    // Filters
    public $search = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'is_active' => 'boolean',
        'position' => 'integer|min:0',
    ];

    public function mount()
    {
        $this->resetFilters();
    }

    public function render()
    {
        $query = ResourceSpecialty::query();

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Order by position
        $resourceSpecialties = $query->orderBy('position')->orderBy('updated_at', 'desc')
                                   ->paginate($this->perPage);

        return view('cms.resource-specialties.index', compact('resourceSpecialties'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function edit($id)
    {
        $resourceSpecialty = ResourceSpecialty::findOrFail($id);

        $this->editingId = $id;
        $this->name = $resourceSpecialty->name;
        $this->description = $resourceSpecialty->description;
        $this->is_active = $resourceSpecialty->is_active;
        $this->position = $resourceSpecialty->position;

        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            $resourceSpecialty = ResourceSpecialty::findOrFail($this->editingId);
            $resourceSpecialty->update($data);
            $this->dispatch('toast', message: 'Especialidad de recurso actualizada exitosamente', type: 'success');
        } else {
            ResourceSpecialty::create($data);
            $this->dispatch('toast', message: 'Especialidad de recurso creada exitosamente', type: 'success');
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
        $resourceSpecialty = ResourceSpecialty::findOrFail($id);

        // Check if there are associated resources
        if ($resourceSpecialty->resources()->count() > 0) {
            $this->dispatch('toast', message: 'No se puede eliminar la especialidad porque tiene recursos asociados', type: 'error');
            return;
        }

        $resourceSpecialty->delete();
        $this->dispatch('toast', message: 'Especialidad de recurso eliminada exitosamente', type: 'success');
    }

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            ResourceSpecialty::where('id', $id)->update(['position' => $index]);
        }
        $this->dispatch('toast', message: 'Orden actualizado exitosamente', type: 'success');
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'slug', 'description',
            'is_active'
        ]);

        $this->is_active = true;
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
}
