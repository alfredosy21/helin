<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Activities;
use App\Models\DeliveryMethod;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class DeliveryMethodsController
 * Manages shipping and delivery method options for the Helin order request flow.
 *
 * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Gestión de Métodos de Entrega | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class DeliveryMethodsController extends Component
{
    use WithPagination;

    /** @var string Display name of the delivery method */
    #[Validate('required|string|max:255')]
    public string $name = '';

    /** @var string|null Slug or internal reference */
    #[Validate('nullable|string|max:255')]
    public ?string $slug = '';

    /** @var string|null Description */
    #[Validate('nullable|string|max:500')]
    public ?string $description = '';

    /** @var int|null ID of the record being modified */
    public ?int $editingId = null;

    /** @var string Search query for real-time filtering */
    public string $search = '';

    /** @var int Pagination limit */
    public int $perPage = 20;

    /** @var bool Form visibility state */
    public bool $showForm = false;

    /** @var bool Active status */
    public bool $is_active = true;

    /** @var bool Global loading indicator */
    public bool $isLoading = false;

    protected string $paginationTheme = 'tailwind';

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, __('cms.abort.delivery_methods'));
        }
    }

    public function render(): View
    {
        $deliveryMethods = DeliveryMethod::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('slug', 'like', "%{$this->search}%"))
            ->orderBy('order', 'asc')
            ->paginate($this->perPage);

        return view('cms.delivery-methods.index', [
            'deliveryMethods' => $deliveryMethods,
        ]);
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('open-form');
    }

    public function save(): void
    {
        $this->isLoading = true;
        $this->validate();

        try {
            $data = [
                'name'        => $this->name,
                'slug'        => $this->slug ?: Str::slug($this->name),
                'description' => $this->description,
                'is_active'   => $this->is_active,
            ];

            if ($this->editingId) {
                $record = DeliveryMethod::findOrFail($this->editingId);
                $record->update($data);

                Activities::saveActivity(__('cms.controllers.delivery_methods.activity_updated', ['id' => $record->id]));
                $this->dispatch('toast', message: __('cms.controllers.delivery_methods.updated'), type: 'success');
            } else {
                DeliveryMethod::query()->increment('order');
                $data['order'] = 1;
                $record = DeliveryMethod::create($data);

                Activities::saveActivity(__('cms.controllers.delivery_methods.activity_created', ['id' => $record->id]));
                $this->dispatch('toast', message: __('cms.controllers.delivery_methods.created'), type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.delivery_methods.process_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    public function edit(int $id): void
    {
        $record = DeliveryMethod::findOrFail($id);

        $this->editingId   = $id;
        $this->name        = $record->name;
        $this->slug        = $record->slug;
        $this->description = $record->description;
        $this->is_active   = $record->is_active;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    public function confirmDelete(int $id): void
    {
        try {
            $record = DeliveryMethod::findOrFail($id);
            $name   = $record->name;
            $record->delete();

            Activities::saveActivity(__('cms.controllers.delivery_methods.activity_deleted', ['name' => $name]));
            $this->dispatch('toast', message: __('cms.controllers.delivery_methods.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.delivery_methods.delete_error'), type: 'error');
        }
    }

    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                DeliveryMethod::query()->where('id', $id)->update(['order' => $index + 1]);
            }

            Activities::saveActivity(__('cms.controllers.delivery_methods.activity_reordered', ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.delivery_methods.order_updated'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.delivery_methods.order_error'), type: 'error');
        }
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('close-form');
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => __('cms.validation_attributes.delivery_method_name'),
        ];
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'slug', 'description', 'is_active', 'editingId']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
}
