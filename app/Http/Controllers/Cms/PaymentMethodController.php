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
    public $slug;
    public $icon;
    public $description;
    public $image;
    public $current_image;
    public $config;
    public $is_active = true;
    public $position = 0;
    public $is_default = false;
    public $provider;
    public $provider_config;
    public $fee_percentage = 0;
    public $fee_fixed = 0;
    public $min_amount;
    public $max_amount;

    // Filters
    public $search = '';
    public $filterProvider = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:payment_methods,slug',
        'icon' => 'nullable|string|max:100',
        'description' => 'nullable|string|max:1000',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'config' => 'nullable|string|max:2000',
        'is_active' => 'boolean',
        'position' => 'integer|min:0',
        'is_default' => 'boolean',
        'provider' => 'nullable|string|max:50',
        'provider_config' => 'nullable|string|max:2000',
        'fee_percentage' => 'numeric|min:0|max:100',
        'fee_fixed' => 'numeric|min:0',
        'min_amount' => 'nullable|numeric|min:0',
        'max_amount' => 'nullable|numeric|min:0',
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
        $this->slug = $method->slug;
        $this->icon = $method->icon;
        $this->description = $method->description;
        $this->current_image = $method->image;
        $this->config = $method->config ? json_encode($method->config) : '';
        $this->is_active = $method->is_active;
        $this->position = $method->position;
        $this->is_default = $method->is_default;
        $this->provider = $method->provider;
        $this->provider_config = $method->provider_config ? json_encode($method->provider_config) : '';
        $this->fee_percentage = $method->fee_percentage;
        $this->fee_fixed = $method->fee_fixed;
        $this->min_amount = $method->min_amount;
        $this->max_amount = $method->max_amount;

        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        // Custom validation for unique slug (except when editing)
        if ($this->editingId) {
            $this->rules['slug'] = 'required|string|max:255|unique:payment_methods,slug,' . $this->editingId;
        }

        $this->validate($this->rules);

        // If setting as default, unset other defaults
        if ($this->is_default) {
            PaymentMethod::where('is_default', true)->update(['is_default' => false]);
        }

        // Simplified data
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

        // Prevent deleting default payment method if it's the only active one
        if ($method->is_default) {
            $activeCount = PaymentMethod::where('is_active', true)->count();
            if ($activeCount <= 1) {
                $this->dispatch('toast', message: 'No se puede eliminar el método de pago por defecto si es el único método activo', type: 'error');
                return;
            }
        }

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
        $this->reset(['search', 'filterProvider']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterProvider()
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
