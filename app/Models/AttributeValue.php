<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * AttributeValue Model
 *
 * Represents specific values for dynamic product attributes with support for
 * visual customization and product relationships.
 *
 * @package App\Models
 */
class AttributeValue extends Model {

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'attribute_id',
        'value',
        'label',
        'description',
        'color',
        'position',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'position' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the attribute that owns the value.
     *
     * @return BelongsTo<Attribute, AttributeValue>
     */
    public function attribute(): BelongsTo {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get the products associated with this attribute value.
     *
     * @return BelongsToMany<Product, AttributeValue>
     */
    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'attribute_value_product')
                        ->withPivot(['notes', 'numeric_value', 'text_value'])
                        ->withTimestamps();
    }

    /**
     * Scope to get only active values.
     *
     * @param Builder<AttributeValue> $query
     * @return Builder<AttributeValue>
     */
    public function scopeActive(Builder $query): Builder {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get values ordered by position.
     *
     * @param Builder<AttributeValue> $query
     * @return Builder<AttributeValue>
     */
    public function scopeOrdered(Builder $query): Builder {
        return $query->orderBy('position', 'asc');
    }

    /**
     * Scope to get values by attribute.
     *
     * @param Builder<AttributeValue> $query
     * @param int $attributeId
     * @return Builder<AttributeValue>
     */
    public function scopeByAttribute(Builder $query, int $attributeId): Builder {
        return $query->where('attribute_id', $attributeId);
    }

    /**
     * Get the display label with fallback to value.
     *
     * @return string
     */
    public function getDisplayLabelAttribute(): string {
        return $this->label ?: $this->value;
    }

    /**
     * Get the formatted value with unit.
     *
     * @return string
     */
    public function getFormattedValueAttribute(): string {
        $value = $this->display_label;

        if ($this->attribute && $this->attribute->unit) {
            $value .= " {$this->attribute->unit}";
        }

        return $value;
    }

    /**
     * Get the color with fallback.
     *
     * @return string
     */
    public function getColorAttribute(): string {
        return $this->color ?? '#6B7280';
    }

    /**
     * Get the style attributes for UI.
     *
     * @return array<string, string>
     */
    public function getStyleAttributesAttribute(): array {
        return [
            'color' => $this->color,
            'background-color' => $this->color . '20',
            'border-color' => $this->color . '40'
        ];
    }

    /**
     * Check if the value has a custom color.
     *
     * @return bool
     */
    public function hasColor(): bool {
        return !empty($this->color);
    }

    /**
     * Get the numeric value for calculations.
     *
     * @return float|null
     */
    public function getNumericValue(): ?float {
        if (is_numeric($this->value)) {
            return (float) $this->value;
        }

        return null;
    }

    /**
     * Get the text value for searching.
     *
     * @return string
     */
    public function getTextValue(): string {
        return strtolower($this->display_label);
    }

    /**
     * Check if this is a boolean value.
     *
     * @return bool
     */
    public function isBoolean(): bool {
        return $this->attribute && $this->attribute->type === 'boolean';
    }

    /**
     * Check if this is a numeric value.
     *
     * @return bool
     */
    public function isNumeric(): bool {
        return $this->attribute && $this->attribute->type === 'number';
    }

    /**
     * Check if this is a select value.
     *
     * @return bool
     */
    public function isSelect(): bool {
        return $this->attribute && $this->attribute->type === 'select';
    }

    /**
     * Check if this is a text value.
     *
     * @return bool
     */
    public function isText(): bool {
        return $this->attribute && $this->attribute->type === 'text';
    }
}
