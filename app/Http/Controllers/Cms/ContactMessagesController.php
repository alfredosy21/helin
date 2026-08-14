<?php

namespace App\Http\Controllers\Cms;

use App\Models\Activities;
use App\Models\ContactMessage;
use App\Models\Module;
use App\Models\Submodule;
use App\Utils\CmsAccess;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Mensajes de Contacto | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class ContactMessagesController extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public $search = '';

    public $readFilter = 'all';

    public $perPage = 20;

    public $showDetails = false;

    public $selectedMessage = null;

    public function mount(): void
    {
        CmsAccess::authorize(Module::CONTACT, Submodule::CONTACT_MESSAGES, __('cms.abort.contact_messages'));
    }

    public function render(): View
    {
        $query = ContactMessage::query()
            ->when($this->search !== '', function ($q) {
                $q->where(function ($inner) {
                    $inner->where('nombre', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('asunto', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->readFilter === 'unread', fn ($q) => $q->unread())
            ->when($this->readFilter === 'read', fn ($q) => $q->where('is_read', true))
            ->latest();

        return view('cms.contact-messages.index', [
            'messages' => $query->paginate($this->perPage),
            'unreadCount' => ContactMessage::unread()->count(),
        ]);
    }

    public function viewDetails(int $id): void
    {
        $this->selectedMessage = ContactMessage::findOrFail($id);

        if (! $this->selectedMessage->is_read) {
            $this->selectedMessage->update(['is_read' => true]);
            Activities::saveActivity(__('cms.controllers.contact_messages.read', ['name' => $this->selectedMessage->nombre]));
        }

        $this->showDetails = true;
    }

    public function closeDetails(): void
    {
        $this->showDetails = false;
        $this->selectedMessage = null;
    }

    public function toggleRead(int $id): void
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => ! $message->is_read]);
        $this->dispatch('toast', message: __('cms.controllers.contact_messages.toggled'), type: 'success');
    }

    public function delete(int $id): void
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        Activities::saveActivity(__('cms.controllers.contact_messages.deleted', ['name' => $message->nombre]));
        $this->dispatch('toast', message: __('cms.controllers.contact_messages.deleted_toast'), type: 'success');

        if ($this->selectedMessage && $this->selectedMessage->id === $id) {
            $this->closeDetails();
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedReadFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }
}
