<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Activities;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class AttributeValuesController
 * Manages the values of dynamic product attributes for the Helin medical catalog.
 *
 * @package App\Http\Controllers\Cms
 */
#[Title('Gestión de Valores de Atributos | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class AttributeValuesController extends Component
{
    use WithPagination;

    /** @var int|null Attribute that owns the value */
    #[Validate('required|exists:attributes,id')]
    public ?int $attribute_id = null;

    /** @var string Value */
    #[Validate('required|string|max:255')]
    public string $value = '';

    /** @var string|null Display label */
    #[Validate('nullable|string|max:255')]
    public ?string $label = '';

    /** @var string|null Description */
    #[Validate('nullable|string|max:1000')]
    public ?string $description = '';

    /** @var string|null Color hex */
    #[Validate('nullable|string|max:20')]
    public ?string $color = '';

    /** @var int|null Record being edited */
    public ?int $editingId = null;

    /** @var string Search query */
    public string $search = '';

    /** @var string|int Filter by attribute */
    public $attributeFilter = 'all';

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
            abort(403, __('cms.abort.attribute_values'));
        }
    }

    public function render(): View
    {
        $attributeValues = AttributeValue::query()
            ->with(['attribute'])
            ->when($this->search, fn($q) => $q->where('value', 'like', "%{$this->search}%")
                ->orWhere('label', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%"))
            ->when($this->attributeFilter !== 'all', fn($q) => $q->where('attribute_id', $this->attributeFilter))
            ->orderBy('attribute_id', 'asc')
            ->orderBy('position', 'asc')
            ->paginate($this->perPage);

        return view('cms.attribute-values.index', [
            'attributeValues' => $attributeValues,
            'attributeList' => Attribute::ordered()->get(),
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
                'attribute_id' => $this->attribute_id,
                'value'        => $this->value,
                'label'        => $this->label,
                'description'  => $this->description,
                'color'        => $this->color,
                'is_active'    => $this->is_active,
            ];

            if ($this->editingId) {
                $record = AttributeValue::findOrFail($this->editingId);
                $record->update($data);

                Activities::saveActivity(__('cms.controllers.attribute_values.activity_updated', ['id' => $record->id]));
                $this->dispatch('toast', message: __('cms.controllers.attribute_values.updated'), type: 'success');
            } else {
                $data['position'] = (int) AttributeValue::where('attribute_id', $this->attribute_id)->max('position') + 1;
                $record = AttributeValue::create($data);

                Activities::saveActivity(__('cms.controllers.attribute_values.activity_created', ['id' => $record->id]));
                $this->dispatch('toast', message: __('cms.controllers.attribute_values.created'), type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.attribute_values.process_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    public function edit(int $id): void
    {
        $record = AttributeValue::findOrFail($id);

        $this->editingId    = $id;
        $this->attribute_id = $record->attribute_id;
        $this->value        = $record->value;
        $this->label        = $record->label;
        $this->description  = $record->description;
        $this->color        = $record->color;
        $this->is_active    = (bool) $record->is_active;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    public function toggle(int $id): void
    {
        try {
            $record = AttributeValue::findOrFail($id);
            $record->update(['is_active' => !$record->is_active]);

            Activities::saveActivity(__('cms.controllers.attribute_values.activity_toggled', ['id' => $id]));
            $this->dispatch('toast', message: __('cms.controllers.attribute_values.toggled'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.attribute_values.process_error'), type: 'error');
        }
    }

    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                AttributeValue::query()->where('id', $id)->update(['position' => $index + 1]);
            }

            Activities::saveActivity(__('cms.controllers.attribute_values.activity_reordered', ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.attribute_values.order_updated'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.attribute_values.order_error'), type: 'error');
        }
    }

    public function confirmDelete(int $id): void
    {
        try {
            $record = AttributeValue::findOrFail($id);
            $value  = $record->value;
            $record->delete();

            Activities::saveActivity(__('cms.controllers.attribute_values.activity_deleted', ['value' => $value]));
            $this->dispatch('toast', message: __('cms.controllers.attribute_values.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.attribute_values.delete_error'), type: 'error');
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
            'attribute_id' => __('cms.attribute_values.attribute_label'),
            'value'        => __('cms.attribute_values.value_label'),
        ];
    }

    private function resetForm(): void
    {
        $this->reset(['attribute_id', 'value', 'label', 'description', 'color', 'editingId']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedAttributeFilter(): void
    {
        $this->resetPage();
    }
}
