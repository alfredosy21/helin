<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model {

    /**
     * Define default settings
     */
    const DEFAULT_SETTINGS = 1;

    /**
     * Name of the table
     * @var type
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'tagline',
        'email',
        'image',
        'address',
        'contact_address',
        'phone',
        'shedule',
        'facebook',
        'youtube',
        'linkedin',
        'instagram',
        'keywords',
        'description',
        'copy',
        'settings_description',
        'analytics_code',
        'opinion_url',
        'offices',
    ];

    protected $casts = [
        'offices' => 'array',
    ];

    /**
     * Get settings
     * @return array | object
     */
    public static function getSettings() {
        $settings = Settings::query()->where('id', self::DEFAULT_SETTINGS)->first();
        return !empty($settings) ? $settings : [];
    }
}
