<?php

namespace App\Http\Controllers\Cms;

use App\Models\PaymentMethod;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

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

        // Process JSON fields
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

        $providerConfigArray = null;
        if ($this->provider_config) {
            try {
                $providerConfigArray = json_decode($this->provider_config, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->addError('provider_config', 'El formato JSON es inválido');
                    return;
                }
            } catch (\Exception $e) {
                $this->addError('provider_config', 'El formato JSON es inválido');
                return;
            }
        }

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'description' => $this->description,
            'config' => $configArray,
            'is_active' => $this->is_active,
            'position' => $this->position,
            'is_default' => $this->is_default,
            'provider' => $this->provider,
            'provider_config' => $providerConfigArray,
            'fee_percentage' => $this->fee_percentage,
            'fee_fixed' => $this->fee_fixed,
            'min_amount' => $this->min_amount,
            'max_amount' => $this->max_amount,
        ];

        // Handle image upload
        if ($this->image) {
            $imagePath = $this->image->store('payment-methods', 'public');
            $data['image'] = $imagePath;
        } elseif ($this->current_image) {
            $data['image'] = $this->current_image;
        }

        if ($this->editingId) {
            $method = PaymentMethod::findOrFail($this->editingId);
            $method->update($data);
            $this->dispatch('showToast', 'Método de pago actualizado exitosamente', 'success');
        } else {
            PaymentMethod::create($data);
            $this->dispatch('showToast', 'Método de pago creado exitosamente', 'success');
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
                $this->dispatch('showToast', 'No se puede eliminar el método de pago por defecto si es el único método activo', 'error');
                return;
            }
        }

        $method->delete();
        $this->dispatch('showToast', 'Método de pago eliminado exitosamente', 'success');
    }

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            PaymentMethod::where('id', $id)->update(['position' => $index]);
        }
        $this->dispatch('showToast', 'Orden actualizado exitosamente', 'success');
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'slug', 'icon', 'description', 'image', 'current_image',
            'config', 'is_active', 'position', 'is_default', 'provider',
            'provider_config', 'fee_percentage', 'fee_fixed', 'min_amount', 'max_amount'
        ]);

        $this->is_active = true;
        $this->position = 0;
        $this->is_default = false;
        $this->fee_percentage = 0;
        $this->fee_fixed = 0;
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
