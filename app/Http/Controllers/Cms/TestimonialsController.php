<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Testimonial;
use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class TestimonialsController
 *
 * Manages social proof assets and customer feedback within the Helin CMS
 * content management system. Handles testimonial lifecycle management with
 * media support and display sequencing. Provides reactive interface for
 * customer feedback organization and presentation.
 *
 * Features:
 * - Customer testimonial management
 * - Professional role and attribution tracking
 * - Media asset support (images, photos)
 * - Display ordering and sequencing
 * - Bulk operations support
 * - Activity logging and audit trail
 * - Role-based access control
 * - Social proof optimization
 *
 * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Gestión de Testimonios | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class TestimonialsController extends Component
{
    use WithPagination;

    /** @var string Author's name */
    #[Validate('required|string|max:255', as: 'nombre')]
    public string $name = '';

    /** @var string Professional role or charge of the author */
    #[Validate('required|string|max:255', as: 'cargo')]
    public string $charge = '';

    /** @var string The actual feedback content */
    #[Validate('required|string|max:2000', as: 'descripción')]
    public string $description = '';

    /** @var string|null Media path (comma-separated or single string) */
    public ?string $image = '';

    /** @var int|null ID of the testimonial being modified */
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
     * Validates user permissions to access testimonial management.
     * Only administrators and content managers can access this module.
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, 'Unauthorized access to Helin Testimonials module.');
        }
    }

    /**
     * Render the component with paginated and sorted testimonials
     *
     * Displays customer testimonials in a tabular format with search capabilities,
     * pagination, and ordering. Includes both active and inactive testimonials
     * for comprehensive management.
     *
     * @return View
     */
    public function render(): View
    {
        $testimonials = Testimonial::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('charge', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate($this->perPage);

        return view('cms.testimonials.index', [
            'testimonials' => $testimonials
        ]);
    }

    /**
     * Prepare the interface for a new testimonial record
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
     * Persist or synchronize testimonial data
     *
     * Handles both creation and update operations with comprehensive validation.
     * Updates activity log and provides user feedback through toast notifications.
     *
     * @return void
     */
    public function save(): void
    {
        $this->isLoading = true;
        $this->validate();

        try {
            $data = [
                'name'        => $this->name,
                'charge'      => $this->charge,
                'description' => $this->description,
                'image'       => $this->image,
            ];

            if ($this->editingId) {
                $testimonial = Testimonial::findOrFail($this->editingId);
                $testimonial->update($data);

                Activities::saveActivity("Testimonio sincronizado: Activo ID #{$testimonial->id}");
                $this->dispatch('toast', message: 'Testimonio actualizado correctamente', type: 'success');
            } else {
                Testimonial::query()->increment('order');
                $data['order'] = 1;

                $testimonial = Testimonial::create($data);

                Activities::saveActivity("Testimonio registrado: Activo ID #{$testimonial->id}");
                $this->dispatch('toast', message: 'Testimonio registrado correctamente', type: 'success');
            }

            $this->cancel();

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al procesar el testimonio', type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Hydrate form with existing testimonial data
     *
     * Loads all testimonial properties into the form for editing.
     * Opens the modal and prepares the interface for modification.
     *
     * @param int $id The testimonial identifier
     * @return void
     */
    public function edit(int $id): void
    {
        $testimonial = Testimonial::findOrFail($id);

        $this->editingId  = $id;
        $this->name       = $testimonial->name;
        $this->charge     = $testimonial->charge;
        $this->description = $testimonial->description;
        $this->image      = $testimonial->image;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Execute testimonial removal after UI confirmation
     *
     * Permanently deletes a customer testimonial and associated data.
     * Updates activity log and provides user feedback.
     * Handles potential constraint violations gracefully.
     *
     * @param int $id The testimonial identifier
     * @return void
     */
    public function confirmDelete(int $id): void
    {
        try {
            $testimonial = Testimonial::findOrFail($id);
            $testimonialName = $testimonial->name;
            $testimonial->delete();

            Activities::saveActivity("Testimonio eliminado: {$testimonialName}");
            $this->dispatch('toast', message: 'Testimonio eliminado correctamente', type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'No se puede eliminar el testimonio. Verifique asociaciones.', type: 'error');
        }
    }

    /**
     * Reorder the display sequence of testimonials via drag & drop data
     *
     * Updates the order field for multiple testimonials in a single operation.
     * Validates the input data and updates activity log for audit trail.
     *
     * @param array $orderedIds Array of IDs in the new order
     * @return void
     */
    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                Testimonial::query()->where('id', $id)->update(['order' => $index + 1]);
            }

            Activities::saveActivity("Testimonios reordenados por Usuario ID #" . Auth::id());
            $this->dispatch('toast', message: 'Orden de testimonios actualizado correctamente', type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al reordenar los testimonios', type: 'error');
        }
    }

    /**
     * Parses the image collection for specialized UI components (Compatibility).
     */
    public function getPhotosProperty(): \Illuminate\Support\Collection
    {
        return collect(explode(',', $this->image ?? ''))
            ->filter()
            ->map(fn($img) => ['name' => trim($img)])
            ->values();
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
        $this->reset(['name', 'charge', 'description', 'image', 'editingId']);
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
    public function getTestimonialLists(): array
    {
        return Testimonial::orderBy('order', 'asc')
                          ->orderBy('name', 'asc')
                          ->get()
                          ->toArray();
    }
}
