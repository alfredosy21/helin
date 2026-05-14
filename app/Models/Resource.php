<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Resource
 * Manages digital assets like case studies, manuals, and videos.
 */
class Resource extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'file_path',
        'thumbnail',
        'resourceable_id',
        'resourceable_type'
    ];

    /**
     * Get the parent resourceable model (Product or Category).
     * * @return MorphTo
     */
    public function resourceable(): MorphTo
    {
        return $this->morphTo();
    }
}