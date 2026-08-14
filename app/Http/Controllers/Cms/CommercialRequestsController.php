<?php

namespace App\Http\Controllers\Cms;

use App\Models\CommercialRequest;
use App\Models\Activities;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Gestión de Solicitudes Comerciales | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class CommercialRequestsController extends Component
{
    use WithPagination;

    public $showForm = false;
    public $showDetails = false;
    public $selectedRequest = null;
    public $search = '';
    public $perPage = 20;
    public $statusFilter = 'all';

    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, __('cms.abort.commercial_requests'));
        }
    }

    public function render()
    {
        $query = CommercialRequest::query()
            ->with(['customerType', 'state', 'city', 'deliveryMethod', 'paymentMethod', 'whatsappNumber'])
            ->when($this->search, function ($query) {
                $query->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('cms.commercial_requests.index', [
            'requests' => $query,
        ]);
    }

    public function viewDetails($id)
    {
        $this->selectedRequest = CommercialRequest::with([
            'customerType',
            'state',
            'city',
            'deliveryMethod',
            'shippingState',
            'shippingCity',
            'paymentMethod',
            'whatsappNumber'
        ])->findOrFail($id);
        $this->showDetails = true;
    }

    public function closeDetails()
    {
        $this->showDetails = false;
        $this->selectedRequest = null;
    }

    public function updateStatus($id, $status)
    {
        try {
            $request = CommercialRequest::findOrFail($id);
            $request->update(['status' => $status]);

            Activities::saveActivity(__('cms.controllers.commercial_requests.activity_status', [
                'id' => $request->id,
                'status' => $status
            ]));

            $this->dispatch('toast', message: __('cms.controllers.commercial_requests.status_updated'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.commercial_requests.status_error'), type: 'error');
        }
    }

    public function delete($id)
    {
        try {
            $request = CommercialRequest::findOrFail($id);
            $request->delete();

            Activities::saveActivity(__('cms.controllers.commercial_requests.activity_deleted', [
                'id' => $request->id
            ]));

            $this->dispatch('toast', message: __('cms.controllers.commercial_requests.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.commercial_requests.delete_error'), type: 'error');
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }
}
