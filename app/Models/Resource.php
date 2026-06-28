<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Resource
 * Manages digital assets like case studies, manuals, and videos.
 */
class Resource extends Model {

    protected $fillable = [
        'title',
        'description',
        'type',
        'file_path',
        'thumbnail',
        'resource_type_id',
        'resource_specialty_id',
        'format',
        'url',
        'is_active',
        'position'
    ];


    /**
     * Get the resource type
     */
    public function resourceType()
    {
        return $this->belongsTo(ResourceType::class);
    }

    /**
     * Get the resource specialty
     */
    public function resourceSpecialty()
    {
        return $this->belongsTo(ResourceSpecialty::class);
    }
}
