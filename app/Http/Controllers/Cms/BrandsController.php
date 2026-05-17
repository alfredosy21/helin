<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Brand;
use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class BrandsController
 *
 * Manages product manufacturers and their associated commercial identities
 * within the Helin CMS content management system. Handles brand lifecycle
 * management with visual customization and display sequencing.
 *
 * Features:
 * - Commercial brand identity management
 * - Logo and visual asset management
 * - Display ordering and sequencing
 * - Bulk operations support
 * - Activity logging and audit trail
 * - Role-based access control
 * - Product association tracking
 *
 * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Gestión de Marcas | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class BrandsController extends Component
{
    use WithPagination;

    /** @var string Commercial name of the brand */
    #[Validate('required|string|max:255', as: 'nombre de la marca')]
    public string $name = '';

    /** @var string|null Brand logo path or reference */
    #[Validate('nullable|string|max:255')]
    public ?string $image = '';

    /** @var string|null Brand description */
    #[Validate('nullable|string|max:1000')]
    public ?string $description = '';

    /** @var string|null SEO description for meta tags */
    #[Validate('nullable|string|max:1000')]
    public ?string $seo_description = '';

    /** @var int|null ID of the brand being modified */
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
     * Validates user permissions to access brand management.
     * Only administrators and content managers can access this module.
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, 'Unauthorized access to Helin Brands module.');
        }
    }

    /**
     * Render the component with paginated and sorted brands
     *
     * Displays commercial brands in a tabular format with search capabilities,
     * pagination, and ordering. Includes both active and inactive brands
     * for comprehensive management.
     *
     * @return View
     */
    public function render(): View
    {
        $brands = Brand::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('image', 'like', "%{$this->search}%");
            })
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate($this->perPage);

        return view('cms.brands.index', [
            'brands' => $brands
        ]);
    }

    /**
     * Prepare the interface for a new brand record
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
     * Persist or synchronize brand data
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
                'name'            => $this->name,
                'image'           => $this->image,
                'description'     => $this->description,
                'seo_description' => $this->seo_description,
            ];

            if ($this->editingId) {
                $brand = Brand::findOrFail($this->editingId);
                $brand->update($data);

                Activities::saveActivity("Marca actualizada: ID #{$brand->id}");
                $this->dispatch('toast', message: 'Marca actualizada correctamente', type: 'success');
            } else {
                Brand::query()->increment('order');
                $data['order'] = 1;

                $brand = Brand::create($data);

                Activities::saveActivity("Marca creada: ID #{$brand->id}");
                $this->dispatch('toast', message: 'Marca creada correctamente', type: 'success');
            }

            $this->cancel();

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al procesar la marca', type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Hydrate form with existing brand data
     *
     * Loads all brand properties into the form for editing.
     * Opens the modal and prepares the interface for modification.
     *
     * @param int $id The brand identifier
     * @return void
     */
    public function edit(int $id): void
    {
        $brand = Brand::findOrFail($id);

        $this->editingId      = $id;
        $this->name           = $brand->name;
        $this->image          = $brand->image;
        $this->description    = $brand->description;
        $this->seo_description = $brand->seo_description;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Execute brand removal after UI confirmation
     *
     * Permanently deletes a commercial brand and associated data.
     * Updates activity log and provides user feedback.
     * Handles potential constraint violations gracefully.
     *
     * @param int $id The brand identifier
     * @return void
     */
    public function confirmDelete(int $id): void
    {
        try {
            $brand = Brand::findOrFail($id);
            $brandName = $brand->name;
            $brand->delete();

            Activities::saveActivity("Marca eliminada: {$brandName}");
            $this->dispatch('toast', message: 'Marca eliminada correctamente', type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'No se puede eliminar la marca. Verifique productos asociados.', type: 'error');
        }
    }

    /**
     * Reorder the display sequence of brands via drag & drop data
     *
     * Updates the order field for multiple brands in a single operation.
     * Validates the input data and updates activity log for audit trail.
     *
     * @param array $orderedIds Array of IDs in the new order
     * @return void
     */
    public function updateOrder(array $orderedIds): void
    {
        try {
            foreach ($orderedIds as $index => $id) {
                Brand::query()->where('id', $id)->update(['order' => $index + 1]);
            }

            Activities::saveActivity("Marcas reordenadas por Usuario ID #" . Auth::id());
            $this->dispatch('toast', message: 'Orden de marcas actualizado correctamente', type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al reordenar las marcas', type: 'error');
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
        $this->reset(['name', 'image', 'description', 'seo_description', 'editingId']);
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
    public function getBrandLists(): array
    {
        return Brand::orderBy('order', 'asc')
                     ->orderBy('name', 'asc')
                     ->get()
                     ->toArray();
    }
}
