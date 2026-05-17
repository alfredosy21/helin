<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Line;
use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class LineController
 *
 * Manages product lines within the Helin CMS content management system.
 * Handles top-level product taxonomy organization and display sequencing.
 * Provides reactive interface for product line lifecycle management.
 *
 * Features:
 * - Top-level product classification management
 * - SEO-friendly URL generation
 * - Display ordering and sequencing
 * - Bulk operations support
 * - Activity logging and audit trail
 * - Role-based access control
 *
 * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Gestión de Líneas | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class LineController extends Component
{
    use WithPagination;

    /** @var string Display name of the product line */
    #[Validate('required|string|max:255', as: 'nombre de la línea')]
    public string $name = '';

    /** @var string|null SEO-friendly slug for URL generation */
    #[Validate('nullable|string|max:255')]
    public ?string $slug = '';

    /** @var string|null Line description */
    #[Validate('nullable|string|max:1000')]
    public ?string $description = '';

    /** @var string|null SEO description for meta tags */
    #[Validate('nullable|string|max:1000')]
    public ?string $seo_description = '';

    /** @var int|null ID of the line being modified */
    public ?int $editingId = null;

    /** @var string Search query for real-time filtering */
    public string $search = '';

    /** @var int Pagination limit */
    public int $perPage = 20;

    /** @var bool Modal visibility state */
    public bool $showForm = false;

    /** @var bool Global loading indicator */
    public bool $isLoading = false;

    /** @var string Custom pagination theme */
    protected string $paginationTheme = 'tailwind';

    /**
     * Component Lifecycle: Authorization Check
     *
     * Validates user permissions to access line management.
     * Only administrators and content managers can access this module.
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, 'Unauthorized access to Helin Lines module.');
        }
    }

    /**
     * Render the component with paginated and sorted lines
     *
     * Displays product lines in a tabular format with search capabilities,
     * pagination, and ordering. Includes both active and inactive lines
     * for comprehensive management.
     *
     * @return View
     */
    public function render(): View
    {
        $lines = Line::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('slug', 'like', "%{$this->search}%");
            })
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate($this->perPage);

        return view('cms.lines.index', [
            'lines' => $lines
        ]);
    }

    /**
     * Prepare the interface for a new line record
     *
     * Initializes form fields with default values and calculates the next
     * order position automatically. Opens the modal for data entry.
     *
     * @return void
     */
    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Persist or synchronize line data
     *
     * Handles both creation and update operations with comprehensive validation.
     * Automatically generates slug if not provided. Updates activity log and
     * provides user feedback through toast notifications.
     *
     * @return void
     */
    public function save(): void
    {
        $this->isLoading = true;

        // Dynamic unique validation
        $this->validate([
            'name' => 'required|string|max:255|unique:lines,name' . ($this->editingId ? ",{$this->editingId}" : ''),
            'slug' => 'nullable|string|max:255|unique:lines,slug' . ($this->editingId ? ",{$this->editingId}" : ''),
        ]);

        try {
            $data = [
                'name'            => $this->name,
                'slug'            => $this->slug ?: Str::slug($this->name),
                'description'     => $this->description,
                'seo_description' => $this->seo_description,
            ];

            if ($this->editingId) {
                $line = Line::findOrFail($this->editingId);
                $line->update($data);

                Activities::saveActivity("Línea de producto actualizada: ID #{$line->id}");
                $this->dispatch('toast', message: 'Línea actualizada correctamente', type: 'success');
            } else {
                Line::query()->increment('order');
                $data['order'] = 1;

                $line = Line::create($data);

                Activities::saveActivity("Línea de producto creada: ID #{$line->id}");
                $this->dispatch('toast', message: 'Línea creada correctamente', type: 'success');
            }

            $this->cancel();

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al procesar la línea de producto', type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Hydrate form with existing line data
     *
     * Loads all line properties into the form for editing.
     * Opens the modal and prepares the interface for modification.
     *
     * @param int $id The line identifier
     * @return void
     */
    public function edit(int $id): void
    {
        $line = Line::findOrFail($id);

        $this->editingId       = $id;
        $this->name            = $line->name;
        $this->slug            = $line->slug;
        $this->description     = $line->description;
        $this->seo_description = $line->seo_description;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Execute line removal after UI confirmation
     *
     * Permanently deletes a product line and associated data.
     * Updates activity log and provides user feedback.
     * Handles potential constraint violations gracefully.
     *
     * @param int $id The line identifier
     * @return void
     */
    public function confirmDelete(int $id): void
    {
        try {
            $line = Line::findOrFail($id);
            $lineName = $line->name;
            $line->delete();

            Activities::saveActivity("Línea de producto eliminada: {$lineName}");
            $this->dispatch('toast', message: 'Línea eliminada correctamente', type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'No se puede eliminar la línea. Verifique productos asociados.', type: 'error');
        }
    }

    /**
     * Reorder the display sequence of lines via drag & drop data
     *
     * Updates the order field for multiple lines in a single operation.
     * Validates the input data and updates activity log for audit trail.
     *
     * @param array $orderedIds Array of IDs in the new order
     * @return void
     */
    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                Line::query()->where('id', $id)->update(['order' => $index + 1]);
            }

            Activities::saveActivity("Líneas de producto reordenadas por Usuario ID #" . Auth::id());
            $this->dispatch('toast', message: 'Orden de líneas actualizado correctamente', type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al actualizar el orden', type: 'error');
        }
    }

    /**
     * Close form and reset internal state
     *
     * Clears all form data, hides the modal, and resets validation state.
     * Dispatches event to notify frontend components of state change.
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('close-form');
    }

    /**
     * Clear all reactive form properties
     *
     * Resets all form fields to their default values and clears validation
     * errors. Used during form initialization and cleanup operations.
     *
     * @return void
     */
    private function resetForm(): void
    {
        $this->reset(['name', 'slug', 'description', 'seo_description', 'editingId']);
        $this->resetValidation();
    }

    /**
     * Lifecycle listener: Pagination reset on search
     *
     * Automatically resets pagination to first page when search query changes.
     * Ensures consistent user experience during search operations.
     *
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Compatibility bridge for legacy frontend calls
     *
     * Provides backward compatibility for existing frontend components
     * that may rely on the old method naming convention.
     *
     * @return array
     */
    public function getLineLists(): array
    {
        return Line::orderBy('order', 'asc')
                   ->orderBy('name', 'asc')
                   ->get()
                   ->toArray();
    }
}
