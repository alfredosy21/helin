<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PageSeo
 *
 * Represents SEO metadata overrides for public site pages.
 *
 * @package App\Models
 */
class PageSeo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_seo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'page_slug',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_image',
    ];

    /**
     * Scope to find a record by page slug.
     */
    public function scopeForPage($query, string $slug)
    {
        return $query->where('page_slug', $slug);
    }
}