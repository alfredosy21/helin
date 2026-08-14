<?php

namespace App\Http\Controllers\Cms;

use App\Models\Activities;
use App\Models\Module;
use App\Models\State;
use App\Models\Submodule;
use App\Models\WhatsAppNumber;
use App\Utils\CmsAccess;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Números de WhatsApp | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class WhatsAppNumbersController extends Component
{
    use WithPagination;

    public $showForm = false;

    public $editingId = null;

    // Form fields
    public $phone_number;

    public $executive_name;

    public $state_id;

    public $is_active = true;

    public $description;

    // Filters
    public $search = '';

    public $stateFilter = 'all';

    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'phone_number' => 'required|digits_between:10,15',
        'executive_name' => 'nullable|string|max:255',
        'state_id' => 'required|exists:states,id',
        'is_active' => 'boolean',
        'description' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        CmsAccess::authorize(Module::SETTINGS, Submodule::WHATSAPP_NUMBERS, __('cms.abort.whatsapp_numbers'));
        $this->resetFilters();
    }

    public function render()
    {
        $query = WhatsAppNumber::query()
            ->with(['state'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('phone_number', 'like', '%'.$this->search.'%')
                        ->orWhere('executive_name', 'like', '%'.$this->search.'%')
                        ->orWhere('description', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->stateFilter !== 'all', function ($query) {
                $query->where('state_id', $this->stateFilter);
            });

        $whatsappNumbers = $query->orderBy('updated_at', 'desc')
            ->paginate($this->perPage);

        return view('cms.whatsapp-numbers.index', [
            'whatsappNumbers' => $whatsappNumbers,
            'states' => State::ordered()->get(),
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function edit($id)
    {
        $whatsappNumber = WhatsAppNumber::findOrFail($id);

        $this->editingId = $id;
        $this->phone_number = $whatsappNumber->phone_number;
        $this->executive_name = $whatsappNumber->executive_name;
        $this->state_id = $whatsappNumber->state_id;
        $this->is_active = $whatsappNumber->is_active;
        $this->description = $whatsappNumber->description;

        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'phone_number' => $this->phone_number,
            'executive_name' => $this->executive_name,
            'state_id' => $this->state_id,
            'is_active' => $this->is_active,
            'description' => $this->description,
        ];

        try {
            if ($this->editingId) {
                $whatsappNumber = WhatsAppNumber::findOrFail($this->editingId);
                $whatsappNumber->update($data);
                Activities::saveActivity(__('cms.controllers.whatsapp_numbers.activity_updated', [
                    'id' => $whatsappNumber->id,
                ]));
                $this->dispatch('toast', message: __('cms.controllers.whatsapp_numbers.updated'), type: 'success');
            } else {
                WhatsAppNumber::create($data);
                Activities::saveActivity(__('cms.controllers.whatsapp_numbers.activity_created'));
                $this->dispatch('toast', message: __('cms.controllers.whatsapp_numbers.created'), type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.whatsapp_numbers.process_error'), type: 'error');
        }
    }

    public function toggle($id)
    {
        try {
            $whatsappNumber = WhatsAppNumber::findOrFail($id);
            $whatsappNumber->update(['is_active' => ! $whatsappNumber->is_active]);

            Activities::saveActivity(__('cms.controllers.whatsapp_numbers.activity_toggled', [
                'id' => $whatsappNumber->id,
            ]));

            $this->dispatch('toast', message: __('cms.controllers.whatsapp_numbers.toggled'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.whatsapp_numbers.process_error'), type: 'error');
        }
    }

    public function confirmDelete($id)
    {
        try {
            $whatsappNumber = WhatsAppNumber::findOrFail($id);
            $whatsappNumber->delete();

            Activities::saveActivity(__('cms.controllers.whatsapp_numbers.activity_deleted', [
                'id' => $id,
            ]));

            $this->dispatch('toast', message: __('cms.controllers.whatsapp_numbers.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.whatsapp_numbers.delete_error'), type: 'error');
        }
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
        $this->editingId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'phone_number', 'executive_name', 'state_id',
            'description',
        ]);

        $this->is_active = true;
    }

    public function resetFilters()
    {
        $this->reset(['search']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStateFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
