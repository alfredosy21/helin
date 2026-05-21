<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

/**
 * Class User
 * 
 * Represents an administrative or client user within the system.
 * Handles authentication, security roles, and profile metadata.
 */
class User extends Authenticatable implements MustVerifyEmail {

    use HasFactory,
        Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id', // Foreign Key para la relación con roles
        'level',
        'image',
        'phone',
        'department',
        'position',
        'biography',
        'is_active', // Estado del usuario
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ maneja el hashing automáticamente si se prefiere
        'is_active' => 'boolean',
        'level' => 'integer',
        'rol_id' => 'integer',
    ];

    /**
     * RELACIÓN: Define la conexión con el modelo Role.
     * 
     * @return BelongsTo
     */
    public function role(): BelongsTo {
        // Vincula el usuario con un rol usando la columna 'rol_id'
        return $this->belongsTo(Role::class, 'rol_id');
    }

    /**
     * Mutator para encriptar la contraseña automáticamente.
     * 
     * @param string|null $value
     * @return void
     */
    public function setPasswordAttribute(?string $value): void {
        if (!empty($value)) {
            // Verifica si el valor ya es un hash para no re-encriptar
            $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
        }
    }

    /**
     * Verifica si el usuario tiene un nivel de administrador global.
     * 
     * @return bool
     */
    public function isRoot(): bool {
        return $this->level === 1;
    }
}
