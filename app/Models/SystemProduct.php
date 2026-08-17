<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SystemProduct extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'seo_description',
        'seo_keywords',
        'is_active',
        'order',
        'banner_title',
        'banner_description',
        'banner_image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
