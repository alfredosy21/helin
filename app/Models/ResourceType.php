<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ResourceType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active',
        'position',
        'config',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    /**
     * Scope to only include active resource types.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by position.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position');
    }

    /**
     * Get all active resource types ordered by position.
     */
    public static function getActiveOrdered(): Builder
    {
        return static::active()->ordered();
    }

    /**
     * Get the resources for this type.
     */
    public function resources()
    {
        return $this->hasMany(Resource::class, 'resource_type_id');
    }

    /**
     * Get configuration value.
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        return data_get($this->config, $key, $default);
    }

    /**
     * Get formatted color for UI.
     */
    public function getFormattedColor(): string
    {
        return $this->color ?? '#6366f1';
    }

    /**
     * Get formatted icon for UI.
     */
    public function getFormattedIcon(): string
    {
        return $this->icon ?? 'fas fa-file';
    }
}
