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
        'is_active',
        'position',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
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
}
