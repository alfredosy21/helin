<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Attribute Model
 *
 * Represents dynamic product attributes with flexible types and validation rules.
 * Supports text, number, select, and boolean field types with custom options.
 *
 * @package App\Models
 */
class Attribute extends Model {

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'unit',
        'options',
        'is_required',
        'is_filterable',
        'position',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'position' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the values associated with the attribute.
     *
     * @return HasMany<AttributeValue, Attribute>
     */
    public function values(): HasMany {
        return $this->hasMany(AttributeValue::class)->orderBy('position', 'asc');
    }

    /**
     * Get the active values for the attribute.
     *
     * @return HasMany<AttributeValue, Attribute>
     */
    public function activeValues(): HasMany {
        return $this->hasMany(AttributeValue::class)
                        ->where('is_active', true)
                        ->orderBy('position', 'asc');
    }

    /**
     * Scope to get only active attributes.
     *
     * @param Builder<Attribute> $query
     * @return Builder<Attribute>
     */
    public function scopeActive(Builder $query): Builder {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get filterable attributes.
     *
     * @param Builder<Attribute> $query
     * @return Builder<Attribute>
     */
    public function scopeFilterable(Builder $query): Builder {
        return $query->where('is_filterable', true);
    }

    /**
     * Scope to get required attributes.
     *
     * @param Builder<Attribute> $query
     * @return Builder<Attribute>
     */
    public function scopeRequired(Builder $query): Builder {
        return $query->where('is_required', true);
    }

    /**
     * Scope to get attributes ordered by position and name.
     *
     * @param Builder<Attribute> $query
     * @return Builder<Attribute>
     */
    public function scopeOrdered(Builder $query): Builder {
        return $query->orderBy('position', 'asc')
                        ->orderBy('name', 'asc');
    }

    /**
     * Scope to get attributes by type.
     *
     * @param Builder<Attribute> $query
     * @param string $type
     * @return Builder<Attribute>
     */
    public function scopeByType(Builder $query, string $type): Builder {
        return $query->where('type', $type);
    }

    /**
     * Get the type label.
     *
     * @return string
     */
    public function getTypeLabelAttribute(): string {
        return match ($this->type) {
            'text' => 'Text',
            'number' => 'Number',
            'select' => 'Select',
            'boolean' => 'Boolean',
            default => ucfirst($this->type)
        };
    }

    /**
     * Get the input type for forms.
     *
     * @return string
     */
    public function getInputTypeAttribute(): string {
        return match ($this->type) {
            'text' => 'text',
            'number' => 'number',
            'select' => 'select',
            'boolean' => 'checkbox',
            default => 'text'
        };
    }

    /**
     * Get the validation rules for this attribute type.
     *
     * @return array<string, string>
     */
    public function getValidationRulesAttribute(): array {
        $rules = [];

        if ($this->is_required) {
            $rules['required'] = 'required';
        }

        return match ($this->type) {
            'text' => array_merge($rules, ['string', 'max:255']),
            'number' => array_merge($rules, ['numeric', 'min:0']),
            'boolean' => array_merge($rules, ['boolean']),
            'select' => array_merge($rules, ['string', 'exists:attribute_values,value']),
            default => $rules
        };
    }

    /**
     * Check if the attribute has options.
     *
     * @return bool
     */
    public function hasOptions(): bool {
        return !empty($this->options);
    }

    /**
     * Get the formatted unit.
     *
     * @return string
     */
    public function getFormattedUnitAttribute(): string {
        return $this->unit ? " ({$this->unit})" : '';
    }

    /**
     * Get the display name with unit.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string {
        return $this->name . $this->formatted_unit;
    }

    /**
     * Check if the attribute supports filtering.
     *
     * @return bool
     */
    public function supportsFiltering(): bool {
        return $this->is_filterable && in_array($this->type, ['select', 'boolean', 'number']);
    }

    /**
     * Get the default value for the attribute type.
     *
     * @return mixed
     */
    public function getDefaultValue(): mixed {
        return match ($this->type) {
            'boolean' => false,
            'number' => 0,
            'text' => '',
            'select' => null,
            default => null
        };
    }
}
