<?php

namespace App\Utils;

use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

/**
 * Class CmsAccess
 *
 * Central authorization helper for CMS Livewire components.
 * Grants access to super administrators (level 1) and to users whose
 * role has the requested module/submodule permission active.
 */
class CmsAccess
{
    /**
     * Authorize the current user against a module/submodule permission.
     *
     * @param  string  $message  403 message (lang key already resolved)
     */
    public static function authorize(int $moduleId, int $submoduleId, string $message = 'Acceso no autorizado.'): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, $message);
        }

        // Super administrators have access to everything
        if ($user->level === 1) {
            return;
        }

        $allowed = Permission::where('rol_id', $user->rol_id)
            ->where('module_id', $moduleId)
            ->where('submodule_id', $submoduleId)
            ->where('type', Permission::SUB_MODULE_TYPE)
            ->where('status', Permission::ACTIVE_STATUS)
            ->exists();

        if (! $allowed) {
            abort(403, $message);
        }
    }
}
