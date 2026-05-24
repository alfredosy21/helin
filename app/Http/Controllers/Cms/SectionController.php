<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Sections;
use App\Models\Activities;
use App\Utils\Helpers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
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
#[Title('Gestión de Secciones | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class SectionController extends Component {

    use WithPagination, WithFileUploads;

    /** @var string Primary title of the section content block */
    #[Validate('required|string|max:255')]
    public string $title = '';

    /** @var string Main HTML or text content for the section */
    #[Validate('required|string')]
    public string $content = '';

    /** @var string|null Label for the Call to Action button */
    #[Validate('nullable|string|max:255')]
    public ?string $name_button = '';

    /** @var string|null Target URL or route for the button */
    #[Validate('nullable|string|max:500')]
    public ?string $url_button = '';

    /** @var array Uploaded image files */
    #[Validate('nullable|array|max:5')]
    #[Validate('image.*', 'image|max:2048')]
    public $image = [];

    /** @var string|null Comma-separated list of image filenames */
    public ?string $imagePaths = '';

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
    public function mount(): void {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, __('cms.abort.sections'));
        }
    }

    /**
     * Render the component view with paginated and filtered data.
     */
    public function render(): View {
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
    public function edit(int $id): void {
        $section = Sections::findOrFail($id);

        $this->editingId = $id;
        $this->title = $section->title;
        $this->content = $section->content;
        $this->name_button = $section->name_button;
        $this->url_button = $section->url_button;
        $this->imagePaths = $section->image;
        $this->status = (bool) $section->status;
        $this->status_content = (bool) $section->status_content;

        $this->loadPhotos();
        $this->showEditForm = true;
        $this->dispatch('open-edit-form');
    }

    /**
     * Commit section modifications to the database.
     */
    public function update(): void {
        $this->validate();
        $this->isLoading = true;

        try {
            $section = Sections::findOrFail($this->editingId);

            // Process new images
            $imagePaths = $this->imagePaths;
            if (!empty($this->image)) {
                foreach ($this->image as $uploadedImage) {
                    $filename = Helpers::generateImageName($uploadedImage, 'section');
                    $path = $uploadedImage->storeAs('sections', $filename, 'public');

                    if ($imagePaths) {
                        $imagePaths .= ',' . $path;
                    } else {
                        $imagePaths = $path;
                    }
                }
            }

            $section->update([
            'title' => $this->title,
            'content' => $this->content,
            'name_button' => $this->name_button,
            'url_button' => $this->url_button,
            'image' => $imagePaths,
            'status' => $this->status ? 1 : 0,
            'status_content' => $this->status_content ? 1 : 0,
        ]);

            Activities::saveActivity(__('cms.controllers.sections.activity_updated', ['id' => $section->id, 'title' => $this->title]));

            $this->imagePaths = $imagePaths;
            $this->image = [];
            $this->loadPhotos();
            $this->dispatch('image-updated');
            $this->dispatch('toast', message: __('cms.controllers.sections.updated'), type: 'success');
            $this->cancelEdit();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.sections.process_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Trigger a confirmation event before deletion.
     *
     * @param int $id
     */
    public function delete(int $id): void {
        $this->dispatch('confirm-delete',
                id: $id,
                message: __('cms.controllers.sections.delete_confirm_message'),
                title: __('cms.controllers.sections.delete_confirm_title')
        );
    }

    /**
     * Permanently remove a section and record the audit trail.
     *
     * @param int $id
     */
    public function confirmDelete(int $id): void {
        try {
            $section = Sections::findOrFail($id);
            $title = $section->title;
            $section->delete();

            Activities::saveActivity(__('cms.controllers.sections.activity_deleted', ['title' => $title]));
            $this->dispatch('toast', message: __('cms.controllers.sections.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.sections.delete_error'), type: 'error');
        }
    }

    /**
     * Unlink a specific media file from the current section's record.
     *
     * @param string $photoName
     */
    public function removePhoto(string $photoName): void {
        if (!$this->imagePaths)
            return;

        try {
            $images = collect(explode(',', $this->imagePaths))
                    ->filter()
                    ->map(fn($img) => trim($img))
                    ->reject(fn($img) => $img === $photoName)
                    ->implode(',');

            $this->imagePaths = $images;

            if ($this->editingId) {
                Sections::query()->where('id', $this->editingId)->update(['image' => $images]);
                Activities::saveActivity(__('cms.controllers.sections.activity_photo_removed', ['id' => $this->editingId]));
            }

            $this->loadPhotos();
            $this->dispatch('toast', message: __('cms.controllers.sections.image_removed'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.sections.image_remove_error'), type: 'error');
        }
    }

    /**
     * Parse the raw image string into a renderable array for the UI.
     */
    private function loadPhotos(): void {
        $this->photos = collect(explode(',', $this->imagePaths ?? ''))
                ->filter()
                ->map(fn($img) => ['name' => trim($img)])
                ->values()
                ->toArray();
    }

    /**
     * Revert form state and hide management interface.
     */
    public function cancelEdit(): void {
        $this->resetForm();
        $this->showEditForm = false;
        $this->dispatch('close-edit-form');
    }

    /**
     * Reset internal state properties and validation errors.
     */
    protected function validationAttributes(): array {
        return [
            'title' => __('cms.validation_attributes.section_title'),
            'content' => __('cms.validation_attributes.section_content'),
            'name_button' => __('cms.validation_attributes.button_label'),
            'url_button' => __('cms.validation_attributes.button_url'),
        ];
    }

    private function resetForm(): void {
        $this->reset(['title', 'content', 'name_button', 'url_button', 'image', 'imagePaths', 'status', 'status_content', 'editingId']);
        $this->resetValidation();
        $this->photos = [];
    }

    /**
     * Lifecycle listener: Reset pagination on search update.
     */
    public function updatedSearch(): void {
        $this->resetPage();
    }

    /**
     * Get formatted section list for legacy API compatibility.
     *
     * @return array
     */
    public function getSectionLists(): array {
        return Sections::orderBy('id', 'asc')->get()->map(fn($section) => [
                    'id' => $section->id,
                    'title' => strip_tags($section->title),
                    'created_at' => $section->created_at->format('m-d-Y H:i:s'),
                        ])->toArray();
    }
}
