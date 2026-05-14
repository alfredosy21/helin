<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

    /**
     * Define type
     */
    const MAIN_MODULE_TYPE = 1;
    const SUB_MODULE_TYPE = 2;

    /**
     * Define status
     */
    const ACTIVE_STATUS = 1;
    const INACTIVE_STATUS = 0;

    /**
     * Name of the table
     * @var type
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['rol_id', 'module_id', 'submodule_id', 'type', 'status'];

    /**
     * Create permissions by role
     * @param string $role_id
     */
    public static function createPermissions($role_id) {
        try {

            foreach (Module::all() as $rs) :
                Permission::create([
                    'rol_id' => $role_id,
                    'module_id' => $rs->id,
                    'type' => self::MAIN_MODULE_TYPE
                ]);
            endforeach;

            foreach (Submodule::all() as $rs) :
                Permission::create([
                    'rol_id' => $role_id,
                    'module_id' => $rs->module_id,
                    'submodule_id' => $rs->id,
                    'type' => self::SUB_MODULE_TYPE
                ]);
            endforeach;
            return true;
        } catch (Exception $ex) {
            report($ex);
        }
        return false;
    }

    /**
     * Get permissions by role
     * @param string $role_id
     * @return array
     */
    public static function getPermissionsByRole($role_id) {
        $permissions_from_modules = Permission::
                join('modules', 'modules.id', '=', 'permissions.module_id')
                ->where('permissions.rol_id', $role_id)
                ->where('permissions.type', self::MAIN_MODULE_TYPE)
                ->select(
                        'permissions.id',
                        'modules.name',
                        'modules.class',
                        'permissions.module_id',
                        'permissions.status'
                )
                ->get();
        $permissions = array();
        foreach ($permissions_from_modules as $rs) {
            $submodules = array();
            $permissions_from_submodules = Permission::
                    join('submodules', 'submodules.id', '=', 'permissions.submodule_id')
                    ->where('permissions.rol_id', $role_id)
                    ->where('permissions.type', self::SUB_MODULE_TYPE)
                    ->where('permissions.module_id', $rs->module_id)
                    ->get([
                'permissions.id',
                'submodules.name',
                'permissions.status'
            ]);
            foreach ($permissions_from_submodules as $row) {
                $submodules[] = array(
                    "id" => $row->id,
                    "name" => $row->name,
                    "status" => $row->status,
                    "completed" => 'false'
                );
            }
            $permissions[] = array(
                "id" => $rs->id,
                "module_id" => $rs->module_id,
                "name" => $rs->name,
                "class" => $rs->class,
                "expanded" => "true",
                "submodules" => $submodules,
                "status" => $rs->status
            );
        }

        return $permissions;
    }
}
