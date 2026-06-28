<?php

namespace App\Http\Controllers\Cms;

use App\Models\PaymentMethod;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Title('Gestión de Métodos de Pago | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class PaymentMethodController extends Component
{
    use WithPagination, WithFileUploads;

    public $showForm = false;
    public $editingId = null;

    // Form fields
    public $name;
    public $description;
    public $is_active = true;

    // Filters
    public $search = '';
    public $filterProvider = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->resetFilters();
    }

    public function render()
    {
        $query = PaymentMethod::query();

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('provider', 'like', '%' . $this->search . '%');
            });
        }

        // Apply filters
        if ($this->filterProvider) {
            $query->where('provider', $this->filterProvider);
        }

        // Order by position
        $paymentMethods = $query->orderBy('position')->orderBy('updated_at', 'desc')
                              ->paginate($this->perPage);

        return view('cms.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function edit($id)
    {
        $method = PaymentMethod::findOrFail($id);

        $this->editingId = $id;
        $this->name = $method->name;
        $this->description = $method->description;
        $this->is_active = $method->is_active;

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
            $method = PaymentMethod::findOrFail($this->editingId);
            $method->update($data);
            $this->dispatch('toast', message: 'Método de pago actualizado exitosamente', type: 'success');
        } else {
            PaymentMethod::create($data);
            $this->dispatch('toast', message: 'Método de pago creado exitosamente', type: 'success');
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
        $method = PaymentMethod::findOrFail($id);
        $method->delete();
        $this->dispatch('toast', message: 'Método de pago eliminado exitosamente', type: 'success');
    }

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            PaymentMethod::where('id', $id)->update(['position' => $index]);
        }
        $this->dispatch('toast', message: 'Orden actualizado exitosamente', type: 'success');
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'description', 'is_active'
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
