<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Activities;
use App\Models\Attribute;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class AttributesController
 * Manages dynamic product attributes for the Helin medical catalog.
 *
 * @package App\Http\Controllers\Cms
 */
#[Title('Gestión de Atributos | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class AttributesController extends Component
{
    use WithPagination;

    /** @var string Attribute display name */
    #[Validate('required|string|max:255')]
    public string $name = '';

    /** @var string|null URL slug */
    #[Validate('nullable|string|max:255')]
    public ?string $slug = '';

    /** @var string Attribute field type */
    #[Validate('required|in:text,number,select,boolean')]
    public string $type = 'text';

    /** @var string|null Description */
    #[Validate('nullable|string|max:1000')]
    public ?string $description = '';

    /** @var string|null Measurement unit */
    #[Validate('nullable|string|max:50')]
    public ?string $unit = '';

    /** @var string|null Options (one per line, for select type) */
    #[Validate('nullable|string')]
    public ?string $options = '';

    /** @var bool Required field */
    #[Validate('boolean')]
    public bool $is_required = false;

    /** @var bool Filterable in catalog */
    #[Validate('boolean')]
    public bool $is_filterable = false;

    /** @var int|null Record being edited */
    public ?int $editingId = null;

    /** @var string Search query */
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
            abort(403, __('cms.abort.attributes'));
        }
    }

    public function render(): View
    {
        $attributeList = Attribute::query()
            ->withCount('values')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('slug', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%"))
            ->orderBy('position', 'asc')
            ->orderBy('name', 'asc')
            ->paginate($this->perPage);

        return view('cms.attributes.index', [
            'attributeList' => $attributeList,
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
                'name'          => $this->name,
                'slug'          => $this->slug ?: Str::slug($this->name),
                'type'          => $this->type,
                'description'   => $this->description,
                'unit'          => $this->unit,
                'options'       => $this->parseOptions(),
                'is_required'   => $this->is_required,
                'is_filterable' => $this->is_filterable,
                'is_active'     => $this->is_active,
            ];

            if ($this->editingId) {
                $record = Attribute::findOrFail($this->editingId);
                $record->update($data);

                Activities::saveActivity(__('cms.controllers.attributes.activity_updated', ['id' => $record->id]));
                $this->dispatch('toast', message: __('cms.controllers.attributes.updated'), type: 'success');
            } else {
                $data['position'] = (int) Attribute::max('position') + 1;
                $record = Attribute::create($data);

                Activities::saveActivity(__('cms.controllers.attributes.activity_created', ['id' => $record->id]));
                $this->dispatch('toast', message: __('cms.controllers.attributes.created'), type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.attributes.process_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    public function edit(int $id): void
    {
        $record = Attribute::findOrFail($id);

        $this->editingId    = $id;
        $this->name         = $record->name;
        $this->slug         = $record->slug;
        $this->type         = $record->type;
        $this->description  = $record->description;
        $this->unit         = $record->unit;
        $this->options      = is_array($record->options) ? implode("\n", $record->options) : '';
        $this->is_required  = (bool) $record->is_required;
        $this->is_filterable = (bool) $record->is_filterable;
        $this->is_active    = (bool) $record->is_active;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    public function toggle(int $id): void
    {
        try {
            $record = Attribute::findOrFail($id);
            $record->update(['is_active' => !$record->is_active]);

            Activities::saveActivity(__('cms.controllers.attributes.activity_toggled', ['id' => $id]));
            $this->dispatch('toast', message: __('cms.controllers.attributes.toggled'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.attributes.process_error'), type: 'error');
        }
    }

    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                Attribute::query()->where('id', $id)->update(['position' => $index + 1]);
            }

            Activities::saveActivity(__('cms.controllers.attributes.activity_reordered', ['user_id' => Auth::id()]));
            $this->dispatch('toast', message: __('cms.controllers.attributes.order_updated'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.attributes.order_error'), type: 'error');
        }
    }

    public function confirmDelete(int $id): void
    {
        try {
            $record = Attribute::findOrFail($id);
            $name   = $record->name;
            $record->delete();

            Activities::saveActivity(__('cms.controllers.attributes.activity_deleted', ['name' => $name]));
            $this->dispatch('toast', message: __('cms.controllers.attributes.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.attributes.delete_error'), type: 'error');
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
            'name' => __('cms.attributes.name_label'),
            'type' => __('cms.attributes.type_label'),
        ];
    }

    private function parseOptions(): ?array
    {
        $options = array_filter(array_map('trim', explode("\n", (string) $this->options)));

        return $options ? array_values($options) : null;
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'slug', 'description', 'unit', 'options', 'editingId']);
        $this->type = 'text';
        $this->is_required = false;
        $this->is_filterable = false;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
}
