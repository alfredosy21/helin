<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\Permission;
use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

/**
 * Class PermissionsController
 *
 * Manages role-based permissions within the Helin CMS.
 * Handles module and submodule permission assignments with real-time
 * updates and comprehensive activity logging.
 *
 * Features:
 * - Module and submodule permission management
 * - Real-time permission status updates
 * - Hierarchical permission structure
 * - Activity logging and audit trail
 * - Role-based access control
 * - Bulk permission operations
 *
 * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('Gestión de Permisos | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class PermissionsController extends Component
{
    use WithPagination;

    /** @var int Role ID being managed */
    public int $roleId;

    /** @var string Role name for display */
    public string $roleName = '';

    /** @var array Permission data structure */
    public array $permissions = [];

    /** @var bool Loading state indicator */
    public bool $isLoading = false;

    /** @var string Custom pagination theme */
    protected string $paginationTheme = 'tailwind';

    /**
     * Component Lifecycle: Authorization and initialization
     *
     * @param int $roleId The role ID to manage permissions for
     * @return void
     */
    public function mount(int $roleId): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, __('cms.abort.permissions'));
        }

        $this->roleId = $roleId;
        $this->getRoleInfo();
        $this->loadPermissions();
    }

    /**
     * Render the permissions management interface
     *
     * @return View
     */
    public function render(): View
    {
        return view('cms.permissions.index');
    }

    /**
     * Load permissions data for the current role
     *
     * @return void
     */
    public function loadPermissions(): void
    {
        $this->isLoading = true;

        try {
            $this->permissions = Permission::getPermissionsByRole($this->roleId);
        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.permissions.load_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Toggle module permission status
     *
     * @param int $moduleId The module ID
     * @return void
     */
    public function toggleModulePermission(int $moduleId): void
    {
        $this->isLoading = true;

        try {
            // Obtener el estado actual de la base de datos
            $permission = Permission::where('rol_id', $this->roleId)
                    ->where('module_id', $moduleId)
                    ->where('type', Permission::MAIN_MODULE_TYPE)
                    ->first();

            if (!$permission) {
                $this->dispatch('toast', message: __('cms.controllers.permissions.module_error'), type: 'error');
                $this->isLoading = false;
                return;
            }

            $newStatus = $permission->status === Permission::ACTIVE_STATUS
                ? Permission::INACTIVE_STATUS
                : Permission::ACTIVE_STATUS;

            $permission->update(['status' => $newStatus]);

            $action = $newStatus === Permission::ACTIVE_STATUS ? __('cms.controllers.permissions.module_activated') : __('cms.controllers.permissions.module_deactivated');
            $actionWord = $newStatus === Permission::ACTIVE_STATUS ? __('cms.controllers.permissions.action_activated') : __('cms.controllers.permissions.action_deactivated');
            Activities::saveActivity(__('cms.controllers.permissions.activity_single_module', ['action' => $actionWord, 'role_id' => $this->roleId, 'module_id' => $moduleId]));

            $this->dispatch('toast',
                message: $action,
                type: $newStatus === Permission::ACTIVE_STATUS ? 'success' : 'warning'
            );

            $this->loadPermissions();

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.permissions.module_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Toggle submodule permission status
     *
     * @param int $permissionId The permission record ID
     * @param int $moduleStatus Parent module status
     * @return void
     */
    public function toggleSubmodulePermission(int $permissionId, int $moduleStatus): void
    {
        // Only allow submodule permission if parent module is active
        if ($moduleStatus !== Permission::ACTIVE_STATUS) {
            $this->dispatch('toast',
                message: __('cms.controllers.permissions.parent_module_required'),
                type: 'warning'
            );
            return;
        }

        $this->isLoading = true;

        try {
            $permission = Permission::findOrFail($permissionId);
            $newStatus = $permission->status === Permission::ACTIVE_STATUS
                ? Permission::INACTIVE_STATUS
                : Permission::ACTIVE_STATUS;

            $permission->update(['status' => $newStatus]);

            $action = $newStatus === Permission::ACTIVE_STATUS ? __('cms.controllers.permissions.submodule_activated') : __('cms.controllers.permissions.submodule_deactivated');
            $actionWord = $newStatus === Permission::ACTIVE_STATUS ? __('cms.controllers.permissions.action_activated') : __('cms.controllers.permissions.action_deactivated');
            Activities::saveActivity(__('cms.controllers.permissions.activity_single_submodule', ['action' => $actionWord, 'permission_id' => $permissionId]));

            $this->dispatch('toast',
                message: $action,
                type: $newStatus === Permission::ACTIVE_STATUS ? 'success' : 'warning'
            );

            $this->loadPermissions();

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.permissions.submodule_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Toggle all modules on/off
     *
     * @param int $status The status to set (1 for active, 0 for inactive)
     * @return void
     */
    public function toggleAllModules(int $status): void
    {
        $this->isLoading = true;

        try {
            Permission::where('rol_id', $this->roleId)
                    ->where('type', Permission::MAIN_MODULE_TYPE)
                    ->update(['status' => $status]);

            // Also update submodules when deactivating all
            if ($status === Permission::INACTIVE_STATUS) {
                Permission::where('rol_id', $this->roleId)
                        ->where('type', Permission::SUB_MODULE_TYPE)
                        ->update(['status' => $status]);
            }

            $action = $status === Permission::ACTIVE_STATUS ? __('cms.controllers.permissions.all_activated') : __('cms.controllers.permissions.all_deactivated');
            $actionWord = $status === Permission::ACTIVE_STATUS ? __('cms.controllers.permissions.action_all_activated') : __('cms.controllers.permissions.action_all_deactivated');
            Activities::saveActivity(__('cms.controllers.permissions.activity_module', ['action' => $actionWord, 'role_id' => $this->roleId]));

            $this->dispatch('toast',
                message: $action,
                type: $status === Permission::ACTIVE_STATUS ? 'success' : 'warning'
            );

            $this->loadPermissions();

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.permissions.all_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Toggle all submodules on/off for active modules
     *
     * @param int $status The status to set
     * @return void
     */
    public function toggleAllSubmodules(int $status, ?int $moduleId = null): void
    {
        $this->isLoading = true;

        try {
            $query = Permission::where('rol_id', $this->roleId)
                    ->where('type', Permission::SUB_MODULE_TYPE);

            if ($moduleId !== null) {
                $query->where('module_id', $moduleId);
            } else {
                $activeModuleIds = Permission::where('rol_id', $this->roleId)
                        ->where('type', Permission::MAIN_MODULE_TYPE)
                        ->where('status', Permission::ACTIVE_STATUS)
                        ->pluck('module_id')
                        ->toArray();
                $query->whereIn('module_id', $activeModuleIds);
            }

            $query->update(['status' => $status]);

            $action = $status === Permission::ACTIVE_STATUS ? __('cms.controllers.permissions.all_sub_activated') : __('cms.controllers.permissions.all_sub_deactivated');
            $actionWord = $status === Permission::ACTIVE_STATUS ? __('cms.controllers.permissions.action_all_activated') : __('cms.controllers.permissions.action_all_deactivated');
            Activities::saveActivity(__('cms.controllers.permissions.activity_submodule', ['action' => $actionWord, 'role_id' => $this->roleId]));

            $this->dispatch('toast',
                message: $action,
                type: $status === Permission::ACTIVE_STATUS ? 'success' : 'warning'
            );

            $this->loadPermissions();

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: __('cms.controllers.permissions.all_sub_error'), type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Get role information for display
     *
     * @return void
     */
    private function getRoleInfo(): void
    {
        try {
            $role = \App\Models\Role::findOrFail($this->roleId);
            $this->roleName = $role->name;
        } catch (\Exception $ex) {
            report($ex);
            $this->roleName = __('cms.controllers.permissions.unknown_role');
        }
    }
}
