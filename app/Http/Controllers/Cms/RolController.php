<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class RolController
 *
 * Manages the security roles within the Helin CMS.
 * This component handles the CRUD operations for the roles table,
 * ensuring strict validation and activity logging.
 *
 * @package App\Http\Controllers\Cms
 * @version 1.1.0
 */
#[Title('Gestión de Roles | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class RolController extends Component {

    use WithPagination;

    /**
     * @var string The unique identifier name of the role.
     */
    #[Validate('required|string|max:255|unique:roles,name')]
    public string $name = '';

    /**
     * @var int|null The ID of the role currently being updated.
     */
    public ?int $editingId = null;

    /**
     * @var string The search term used for filtering the roles list.
     */
    public string $search = '';

    /**
     * @var int The number of records to display per page.
     */
    public int $perPage = 20;

    /**
     * @var bool Controls the visibility of the creation/edition modal.
     */
    public bool $showForm = false;

    /**
     * @var bool Indicates if the component is performing an async operation.
     */
    public bool $isLoading = false;

    /**
     * @var string The Tailwind CSS theme for pagination.
     */
    protected string $paginationTheme = 'tailwind';

    /**
     * Component Lifecycle: Security check for administrative access.
     *
     * @return void
     */
    public function mount(): void {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== Role::ADMINISTRATOR && $user->level !== Role::ADMINISTRATOR)) {
            abort(403, __('cms.abort.roles'));
        }
    }

    /**
     * Render the component view with paginated and filtered roles.
     *
     * @return View
     */
    public function render(): View {
        $roles = Role::query()
                ->where('id', '>', 1)
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', "%{$this->search}%");
                })
                ->orderBy('id', 'desc')
                ->paginate($this->perPage);

        return view('cms.roles.index', [
            'roles' => $roles
        ]);
    }

    /**
     * Open the form modal and reset the state for a new record.
     *
     * @return void
     */
    public function create(): void {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Save the role data into the database (Create or Update).
     *
     * @return void
     */
    public function save(): void {
        $this->isLoading = true;

        // Dynamic validation to exclude the current ID from the unique check during updates
        $rules = [
            'name' => 'required|string|max:255|unique:roles,name' . ($this->editingId ? ",{$this->editingId}" : ''),
        ];

        $this->validate($rules);

        try {
            if ($this->editingId) {
                // Prevent updating roles with ID <= 1 (protected system roles)
                if ($this->editingId <= 1) {
                    $this->dispatch('toast', message: __('cms.controllers.roles.system_role_edit_error'), type: 'error');
                    return;
                }

                $role = Role::findOrFail($this->editingId);
                $role->update(['name' => $this->name]);

                Activities::saveActivity(__('cms.controllers.roles.activity_updated', ['name' => $this->name, 'id' => $role->id]));
                $this->dispatch('toast', message: __('cms.controllers.roles.updated'), type: 'success');
            } else {
                $role = Role::create(['name' => $this->name]);

                // Create permissions for the new role
                Permission::createPermissions($role->id);

                Activities::saveActivity(__('cms.controllers.roles.activity_created', ['name' => $this->name, 'id' => $role->id]));
                $this->dispatch('toast', message: __('cms.controllers.roles.created'), type: 'success');
            }

            $this->cancel();
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.roles.process_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Load an existing role into the form for edition.
     *
     * @param int $id The unique identifier of the role.
     * @return void
     */
    public function edit(int $id): void {
        // Prevent editing roles with ID <= 1 (protected system roles)
        if ($id <= 1) {
            $this->dispatch('toast', message: __('cms.controllers.roles.system_role_edit_error'), type: 'error');
            return;
        }

        $role = Role::findOrFail($id);

        $this->editingId = $id;
        $this->name = $role->name;

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Remove a role from the database.
     *
     * @param int $id The unique identifier of the role.
     * @return void
     */
    public function confirmDelete(int $id): void {
        // Prevent deleting roles with ID <= 1 (protected system roles)
        if ($id <= 1) {
            $this->dispatch('toast', message: __('cms.controllers.roles.system_role_edit_error'), type: 'error');
            return;
        }

        try {
            $role = Role::findOrFail($id);
            $roleName = $role->name;
            $role->delete();

            Activities::saveActivity(__('cms.controllers.roles.activity_deleted', ['name' => $roleName]));
            $this->dispatch('toast', message: __('cms.controllers.roles.deleted'), type: 'success');
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.roles.system_role_delete_error'), type: 'error');
        }
    }

    /**
     * Close the form modal and clear validation/state.
     *
     * @return void
     */
    public function cancel(): void {
        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('close-form');
    }

    /**
     * Reset the public properties and validation errors.
     *
     * @return void
     */
    protected function validationAttributes(): array {
        return [
            'name' => __('cms.validation_attributes.role_name'),
        ];
    }

    private function resetForm(): void {
        $this->reset(['name', 'editingId']);
        $this->resetValidation();
    }

    /**
     * Reset pagination when the search query is updated.
     *
     * @return void
     */
    public function updatedSearch(): void {
        $this->resetPage();
    }
}
