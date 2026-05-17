<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Role
 * 
 * Represents the security roles within the Helin CMS.
 * This model is strictly mapped to the roles table structure.
 * 
 * @package App\Models
 * @version 1.1.0
 */
class Role extends Model
{
    use HasFactory;

    /**
     * @var string The table associated with the model.
     */
    protected $table = 'roles';

    /**
     * Role ID constants for core system logic.
     */
    public const ADMINISTRATOR = 1;
    public const EDITOR = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * RELATIONS
     */

    /**
     * Get the users associated with this role.
     * 
     * @return HasMany
     */
    public function users(): HasMany
    {
        // Links users where 'rol_id' matches this role's ID
        return $this->hasMany(User::class, 'rol_id');
    }

    /**
     * Get the permission matrix associated with this role.
     * 
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        // Links the permission matrix entries for this specific role
        return $this->hasMany(Permission::class, 'rol_id');
    }
}