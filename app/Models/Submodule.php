<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submodule extends Model {

    /**
     * Name of the table
     * @var type
     */
    protected $table = 'submodules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'url', 'module_id'];
}
