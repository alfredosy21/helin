<?php

declare(strict_types=1);


namespace App\Http\Controllers\Cms;

use App\Models\Sections;
use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class SectionController
 * 
 * Reactive management interface for Helin platform content blocks.
 * Provides real-time CRUD operations, media detachment logic, and audit logging.
 * 
 * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Section Management | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class SectionController extends Component
{
    use WithPagination;

    /** @var string Primary title of the section content block */
    #[Validate('required|string|max:255', as: 'section title')]
    public string $title = '';

    /** @var string Main HTML or text content for the section */
    #[Validate('required|string', as: 'section content')]
    public string $content = '';

    /** @var string|null Label for the Call to Action button */
    #[Validate('nullable|string|max:255', as: 'button label')]
    public ?string $name_button = '';

    /** @var string|null Target URL or route for the button */
    #[Validate('nullable|string|max:500', as: 'button URL')]
    public ?string $url_button = '';

    /** @var string|null Comma-separated list of image filenames */
    #[Validate('nullable|string')]
    public ?string $image = '';

    /** @var bool Public visibility status */
    public bool $status = false;

    /** @var bool Content display toggle within the layout */
    public bool $status_content = false;

    /** @var int|null ID of the section currently being modified */
    public ?int $editingId = null;

    /** @var string Search query for filtering the section list */
    public string $search = '';

    /** @var int Pagination limit per page */
    public int $perPage = 20;

    /** @var bool Modal/Form visibility state */
    public bool $showEditForm = false;

    /** @var array Formatted photo collection for UI rendering */
    public array $photos = [];

    /** @var bool Global loading indicator state */
    public bool $isLoading = false;

    /** @var string Custom pagination theme */
    protected string $paginationTheme = 'tailwind';

    /**
     * Component Lifecycle: Authorization Check.
     * Ensure only verified administrators can access the section management.
     */
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, 'Unauthorized access to Helin CMS core modules.');
        }
    }

    /**
     * Render the component view with paginated and filtered data.
     */
    public function render(): View
    {
        $sections = Sections::query()
            ->when($this->search, fn($query) => $query->where('title', 'like', "%{$this->search}%"))
            ->orderBy('id', 'asc')
            ->paginate($this->perPage);

        return view('cms.sections.index', [
            'sections' => $sections
        ]);
    }

    /**
     * Hydrate the form properties with existing section data.
     * 
     * @param int $id
     */
    public function edit(int $id): void
    {
        $section = Sections::findOrFail($id);

        $this->editingId = $id;
        $this->title = $section->title;
        $this->content = $section->content;
        $this->name_button = $section->name_button;
        $this->url_button = $section->url_button;
        $this->image = $section->image;
        $this->status = (bool) $section->status;
        $this->status_content = (bool) $section->status_content;

        $this->loadPhotos();
        $this->showEditForm = true;
        $this->dispatch('open-edit-form');
    }

    /**
     * Commit section modifications to the database.
     */
    public function update(): void
    {
        $this->validate();
        $this->isLoading = true;

        try {
            $section = Sections::findOrFail($this->editingId);

            $section->update([
                'title' => $this->title,
                'content' => $this->content,
                'name_button' => $this->name_button,
                'url_button' => $this->url_button,
                'image' => $this->image,
                'status' => $this->status ? 1 : 0,
                'status_content' => $this->status_content ? 1 : 0,
            ]);

            Activities::saveActivity("Section Synchronized: ID #{$section->id} ({$this->title})");

            $this->dispatch('toast', message: 'Sección actualizada correctamente', type: 'success');
            $this->cancelEdit();

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Ocurrió un error al procesar la solicitud', type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Trigger a confirmation event before deletion.
     * 
     * @param int $id
     */
    public function delete(int $id): void
    {
        $this->dispatch('confirm-delete',
            id: $id,
            message: '¿Estás seguro de eliminar esta sección? Esta acción no se puede deshacer.',
            title: '¡Cuidado!'
        );
    }

    /**
     * Permanently remove a section and record the audit trail.
     * 
     * @param int $id
     */
    public function confirmDelete(int $id): void
    {
        try {
            $section = Sections::findOrFail($id);
            $title = $section->title;
            $section->delete();

            Activities::saveActivity("Section Decommissioned: {$title}");
            $this->dispatch('toast', message: 'Sección eliminada correctamente', type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error: La sección contiene datos protegidos.', type: 'error');
        }
    }

    /**
     * Unlink a specific media file from the current section's record.
     * 
     * @param string $photoName
     */
    public function removePhoto(string $photoName): void
    {
        if (!$this->image) return;

        try {
            $images = collect(explode(',', $this->image))
                ->filter()
                ->map(fn($img) => trim($img))
                ->reject(fn($img) => $img === $photoName)
                ->implode(',');

            $this->image = $images;

            if ($this->editingId) {
                Sections::query()->where('id', $this->editingId)->update(['image' => $images]);
                Activities::saveActivity("Media asset detached from Section ID #{$this->editingId}");
            }

            $this->loadPhotos();
            $this->dispatch('toast', message: 'Imagen eliminada correctamente', type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al eliminar la imagen', type: 'error');
        }
    }

    /**
     * Parse the raw image string into a renderable array for the UI.
     */
    private function loadPhotos(): void
    {
        $this->photos = collect(explode(',', $this->image ?? ''))
            ->filter()
            ->map(fn($img) => ['name' => trim($img)])
            ->values()
            ->toArray();
    }

    /**
     * Revert form state and hide management interface.
     */
    public function cancelEdit(): void
    {
        $this->resetForm();
        $this->showEditForm = false;
        $this->dispatch('close-edit-form');
    }

    /**
     * Reset internal state properties and validation errors.
     */
    private function resetForm(): void
    {
        $this->reset(['title', 'content', 'name_button', 'url_button', 'image', 'status', 'status_content', 'editingId']);
        $this->resetValidation();
        $this->photos = [];
    }

    /**
     * Lifecycle listener: Reset pagination on search update.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Get formatted section list for legacy API compatibility.
     * 
     * @return array
     */
    public function getSectionLists(): array
    {
        return Sections::orderBy('id', 'asc')->get()->map(fn($section) => [
            'id' => $section->id,
            'title' => strip_tags($section->title),
            'created_at' => $section->created_at->format('m-d-Y H:i:s'),
        ])->toArray();
    }
}