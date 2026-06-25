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
        'caracas_whatsapp',
        'caracas_location',
        'valencia_whatsapp',
        'valencia_location',
        'barquisimeto_whatsapp',
        'barquisimeto_location',
        'maracay_whatsapp',
        'maracay_location',
        'maracaibo_whatsapp',
        'maracaibo_location',
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
