<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\Submodule;
use App\Models\Module;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermissionMiddleware {

    /**
     * Handle an incoming request based on module/submodule permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $moduleNames
     * @param  string|null  $submoduleNames
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $moduleNames, ?string $submoduleNames = null) {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Convert module names to arrays (handle comma-separated values)
        $moduleArray = explode(',', $moduleNames);
        $submoduleArray = $submoduleNames ? explode(',', $submoduleNames) : [];

        // Debug logging
        Log::info('PermissionMiddleware check', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role_id' => $user->rol_id,
            'required_module_names' => $moduleNames,
            'required_submodule_names' => $submoduleNames
        ]);

        // Super administrators (level 1) have access to everything
        if ($user->level === 1) {
            Log::info('Access granted: Super administrator access');
            return $next($request);
        }

        // Check if user has permission for any of the specified modules
        $hasModulePermission = false;
        foreach ($moduleArray as $moduleName) {
            $moduleName = trim($moduleName);
            if ($this->hasModulePermissionByName($user->rol_id, $moduleName)) {
                $hasModulePermission = true;
                break;
            }
        }

        if (!$hasModulePermission) {
            Log::warning('Access denied: Module permission not found', [
                'role_id' => $user->rol_id,
                'module_names' => $moduleArray
            ]);
            abort(403, 'No tienes permisos para acceder a este módulo.');
        }

        // If submodules are specified, check if user has permission for any of them
        if (!empty($submoduleArray)) {
            $hasSubmodulePermission = false;
            foreach ($submoduleArray as $submoduleName) {
                $submoduleName = trim($submoduleName);
                if ($this->hasSubmodulePermissionByName($user->rol_id, $submoduleName)) {
                    $hasSubmodulePermission = true;
                    break;
                }
            }

            if (!$hasSubmodulePermission) {
                Log::warning('Access denied: Submodule permission not found', [
                    'role_id' => $user->rol_id,
                    'submodule_names' => $submoduleArray
                ]);
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }
        }

        Log::info('Access granted for user ' . $user->email);
        return $next($request);
    }

    /**
     * Check if user has permission for the specified module.
     *
     * @param int $roleId
     * @param int $moduleId
     * @return bool
     */
    private function hasModulePermission(int $roleId, int $moduleId): bool {
        return Permission::where('rol_id', $roleId)
                        ->where('module_id', $moduleId)
                        ->where('type', Permission::MAIN_MODULE_TYPE)
                        ->where('status', Permission::ACTIVE_STATUS)
                        ->exists();
    }

    /**
     * Check if user has permission for the specified submodule.
     *
     * @param int $roleId
     * @param int $moduleId
     * @param int $submoduleId
     * @return bool
     */
    private function hasSubmodulePermission(int $roleId, int $moduleId, int $submoduleId): bool {
        return Permission::join('submodules', 'submodules.id', '=', 'permissions.submodule_id')
                        ->where('permissions.rol_id', $roleId)
                        ->where('permissions.module_id', $moduleId)
                        ->where('permissions.submodule_id', $submoduleId)
                        ->where('type', Permission::SUB_MODULE_TYPE)
                        ->where('status', Permission::ACTIVE_STATUS)
                        ->exists();
    }

    /**
     * Check if user has permission for the specified route.
     * Alternative method for route-based permission checking.
     *
     * @param int $roleId
     * @param string $route
     * @return bool
     */
    public static function hasRoutePermission(int $roleId, string $route): bool {
        // Find submodule by URL
        $submodule = Submodule::where('url', $route)->first();

        if (!$submodule) {
            return false;
        }

        return Permission::join('submodules', 'submodules.id', '=', 'permissions.submodule_id')
                        ->where('permissions.rol_id', $roleId)
                        ->where('permissions.submodule_id', $submodule->id)
                        ->where('permissions.type', Permission::SUB_MODULE_TYPE)
                        ->where('permissions.status', Permission::ACTIVE_STATUS)
                        ->exists();
    }

    /**
     * Get all active permissions for a user role.
     *
     * @param int $roleId
     * @return array
     */
    public static function getUserPermissions(int $roleId): array {
        $permissions = [];

        // Get active modules
        $modules = Permission::join('modules', 'modules.id', '=', 'permissions.module_id')
                ->where('permissions.rol_id', $roleId)
                ->where('permissions.type', Permission::MAIN_MODULE_TYPE)
                ->where('permissions.status', Permission::ACTIVE_STATUS)
                ->pluck('modules.name')
                ->toArray();

        $permissions['modules'] = $modules;

        // Get active submodules
        $submodules = Permission::join('submodules', 'submodules.id', '=', 'permissions.submodule_id')
                ->join('modules', 'modules.id', '=', 'permissions.module_id')
                ->where('permissions.rol_id', $roleId)
                ->where('permissions.type', Permission::SUB_MODULE_TYPE)
                ->where('permissions.status', Permission::ACTIVE_STATUS)
                ->get(['modules.name as module', 'submodules.name as submodule', 'submodules.url'])
                ->toArray();

        $permissions['submodules'] = $submodules;

        return $permissions;
    }

    /**
     * Check if user has permission for the specified module by name.
     *
     * @param int $roleId
     * @param string $moduleName
     * @return bool
     */
    private function hasModulePermissionByName(int $roleId, string $moduleName): bool {
        $module = Module::where('name', $moduleName)->first();

        if (!$module) {
            Log::warning('Module not found', ['module_name' => $moduleName]);
            return false;
        }

        return Permission::where('rol_id', $roleId)
                        ->where('module_id', $module->id)
                        ->where('type', Permission::MAIN_MODULE_TYPE)
                        ->where('status', Permission::ACTIVE_STATUS)
                        ->exists();
    }

    /**
     * Check if user has permission for the specified submodule by name.
     *
     * @param int $roleId
     * @param string $submoduleName
     * @return bool
     */
    private function hasSubmodulePermissionByName(int $roleId, string $submoduleName): bool {
        $submodule = Submodule::where('name', $submoduleName)->first();

        if (!$submodule) {
            Log::warning('Submodule not found', ['submodule_name' => $submoduleName]);
            return false;
        }

        return Permission::join('submodules', 'submodules.id', '=', 'permissions.submodule_id')
                        ->where('permissions.rol_id', $roleId)
                        ->where('permissions.submodule_id', $submodule->id)
                        ->where('permissions.type', Permission::SUB_MODULE_TYPE)
                        ->where('permissions.status', Permission::ACTIVE_STATUS)
                        ->exists();
    }
}
