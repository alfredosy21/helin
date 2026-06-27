<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\Submodule;
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
     * @param  int  $moduleId
     * @param  int|null  $submoduleId
     * @return mixed
     */
    public function handle(Request $request, Closure $next, int $moduleId, ?int $submoduleId = null) {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Debug logging
        Log::info('PermissionMiddleware check', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role_id' => $user->rol_id,
            'required_module_id' => $moduleId,
            'required_submodule_id' => $submoduleId
        ]);

        // Super administrators (level 1) have access to everything
        if ($user->level === 1) {
            Log::info('Access granted: Super administrator access');
            return $next($request);
        }

        // Check module permission
        if (!$this->hasModulePermission($user->rol_id, $moduleId)) {
            Log::warning('Access denied: Module permission not found', [
                'role_id' => $user->rol_id,
                'module_id' => $moduleId
            ]);
            abort(403, 'No tienes permisos para acceder a este módulo.');
        }

        // If submodule is specified, check submodule permission
        if ($submoduleId && !$this->hasSubmodulePermission($user->rol_id, $moduleId, $submoduleId)) {
            Log::warning('Access denied: Submodule permission not found', [
                'role_id' => $user->rol_id,
                'module_id' => $moduleId,
                'submodule_id' => $submoduleId
            ]);
            abort(403, 'No tienes permisos para acceder a esta sección.');
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
}
