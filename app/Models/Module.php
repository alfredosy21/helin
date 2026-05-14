<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model {

    /**
     * Name of the table
     * @var type
     */
    protected $table = 'modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'class', 'position', 'is_active'];

    /**
     * Get all modules avaliables by user role
     * @return array
     */
    public static function getModules() {

        $modules = array();
        if (auth()->guard('web')->User()->level == 1) {
            $modules = Module::with('submodules')->orderBy('position')->get()->toArray();
        } else {
            $permissions_from_modules = Permission::
                    join('modules', 'modules.id', '=', 'permissions.module_id')
                    ->where('permissions.rol_id', auth()->guard('web')->User()->rol_id)
                    ->where('permissions.status', Permission::ACTIVE_STATUS)
                    ->where('permissions.type', Permission::MAIN_MODULE_TYPE)
                    ->select(
                            'modules.name',
                            'modules.class',
                            'permissions.module_id',
                    )
                    ->get();

            foreach ($permissions_from_modules as $rs) {
                $modules[] = array(
                    "name" => $rs->name,
                    "class" => $rs->class,
                    "submodules" => self::getSubModules($rs->module_id),
                );
            }
        }

        return !empty($modules) ? $modules : [];
    }

    /**
     * Get all submodules avaliables by module id
     * @param string $module_id
     * @return array
     */
    public static function getSubModules($module_id) {
        $submodules = array();
        $permissions_from_submodules = Permission::
                join('submodules', 'submodules.id', '=', 'permissions.submodule_id')
                ->where('permissions.rol_id', auth()->guard('web')->User()->rol_id)
                ->where('permissions.type', Permission::SUB_MODULE_TYPE)
                ->where('permissions.status', Permission::ACTIVE_STATUS)
                ->where('permissions.module_id', $module_id)
                ->get([
            'submodules.url',
            'submodules.name'
        ]);
        foreach ($permissions_from_submodules as $row) {
            $submodules[] = array(
                "name" => $row->name,
                "url" => $row->url
            );
        }
        return $submodules;
    }

    /**
     * The commissions that belong to the modules.
     */
    public function submodules() {
        return $this->hasMany(Submodule::class, 'module_id', 'id');
    }
}
