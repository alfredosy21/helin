<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Menus extends Model {

    /**
     * Name of the table
     * @var string
     */
    protected $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'url',
        'type',
        'position',
        'image',
        'status',
        'parent_id',
        'target_blank',
        'description',
        'icon'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
        'target_blank' => 'boolean',
        'position' => 'integer',
        'type' => 'integer',
        'parent_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Menu types constants
     */
    const TYPE_HEADER = 1;
    const TYPE_FOOTER = 2;
    const TYPE_SIDEBAR = 3;

    /**
     * Parent relationship for nested menus
     */
    public function parent() {
        return $this->belongsTo(Menus::class, 'parent_id');
    }

    /**
     * Children relationship for nested menus
     */
    public function children() {
        return $this->hasMany(Menus::class, 'parent_id')
                        ->orderBy('position', 'asc');
    }

    /**
     * Scope to get active menus
     */
    public function scopeActive(Builder $query): Builder {
        return $query->where('status', true);
    }

    /**
     * Scope to get menus by type
     */
    public function scopeByType(Builder $query, int $type): Builder {
        return $query->where('type', $type);
    }

    /**
     * Scope to get root menus (no parent)
     */
    public function scopeRoot(Builder $query): Builder {
        return $query->whereNull('parent_id');
    }

    /**
     * Set title attribute
     * @param string $value
     */
    public function setTitleAttribute($value) {
        $value = strip_tags($value);
        $value = preg_replace('/[^a-zA-Z0-9á-źÁ-Ź[\s]/s', '', $value);
        $value = trim($value);
        $this->attributes['title'] = $value;
    }

    /**
     * Set url attribute
     * @param string $value
     */
    public function setUrlAttribute($value) {
        $value = strtolower($value);
        $value = mb_strtolower($value, 'UTF-8');
        $value = preg_replace('/[^a-zA-Z0-9[#&=[\/]-_.:\s]/s', '', $value);
        $value = str_replace('https://', 'http://', $value);
        $value = str_replace('www.', 'http://', $value);
        $value = str_replace('http://http://', 'http://', $value);
        $value = trim($value);
        $this->attributes['url'] = $value;
    }

    /**
     * Get available header menu items
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getHeaderItems() {
        return self::active()
                        ->byType(self::TYPE_HEADER)
                        ->root()
                        ->with(['children' => function ($query) {
                                $query->active()->orderBy('position', 'asc');
                            }])
                        ->orderBy('position', 'asc')
                        ->get();
    }

    /**
     * Get available footer menu items
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFooterItems() {
        return self::active()
                        ->byType(self::TYPE_FOOTER)
                        ->root()
                        ->with(['children' => function ($query) {
                                $query->active()->orderBy('position', 'asc');
                            }])
                        ->orderBy('position', 'asc')
                        ->get();
    }

    /**
     * Get available sidebar menu items
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getSidebarItems() {
        return self::active()
                        ->byType(self::TYPE_SIDEBAR)
                        ->root()
                        ->with(['children' => function ($query) {
                                $query->active()->orderBy('position', 'asc');
                            }])
                        ->orderBy('position', 'asc')
                        ->get();
    }

    /**
     * Get available website menu items (legacy compatibility)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAvailableItems() {
        return self::active()
                        ->orderBy('position', 'asc')
                        ->get();
    }

    /**
     * Get menu types as array
     * @return array
     */
    public static function getTypes(): array {
        return [
            self::TYPE_HEADER => 'Header',
            self::TYPE_FOOTER => 'Footer',
            self::TYPE_SIDEBAR => 'Sidebar'
        ];
    }

    /**
     * Get type label
     * @return string
     */
    public function getTypeLabelAttribute(): string {
        $types = self::getTypes();
        return $types[$this->type] ?? 'Unknown';
    }

    /**
     * Check if menu has children
     * @return bool
     */
    public function hasChildren(): bool {
        return $this->children()->count() > 0;
    }

    /**
     * Get full URL with protocol
     * @return string
     */
    public function getFullUrlAttribute(): string {
        $url = $this->url;

        // If it's a relative URL, prepend base URL
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $url = url($url);
        }

        return $url;
    }
}
