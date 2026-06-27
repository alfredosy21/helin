<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PaymentMethod extends Model
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
        'icon',
        'description',
        'image',
        'config',
        'is_active',
        'position',
        'is_default',
        'provider',
        'provider_config',
        'fee_percentage',
        'fee_fixed',
        'min_amount',
        'max_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'config' => 'array',
        'provider_config' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'fee_percentage' => 'decimal:2',
        'fee_fixed' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
    ];

    /**
     * Scope to only include active payment methods.
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
     * Scope to get default payment method.
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    /**
     * Get the default payment method.
     */
    public static function getDefault(): ?self
    {
        return static::active()->default()->first();
    }

    /**
     * Get all active payment methods ordered by position.
     */
    public static function getActiveOrdered(): Builder
    {
        return static::active()->ordered();
    }

    /**
     * Calculate total fee for a given amount.
     */
    public function calculateFee(float $amount): float
    {
        $percentageFee = $amount * ($this->fee_percentage / 100);
        $fixedFee = $this->fee_fixed;
        
        return $percentageFee + $fixedFee;
    }

    /**
     * Get total amount including fees.
     */
    public function getTotalWithFees(float $amount): float
    {
        return $amount + $this->calculateFee($amount);
    }

    /**
     * Check if amount is within limits.
     */
    public function isAmountValid(float $amount): bool
    {
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }
        
        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }
        
        return true;
    }

    /**
     * Get formatted fee information.
     */
    public function getFormattedFee(): string
    {
        $fees = [];
        
        if ($this->fee_percentage > 0) {
            $fees[] = "{$this->fee_percentage}%";
        }
        
        if ($this->fee_fixed > 0) {
            $fees[] = "$" . number_format($this->fee_fixed, 2);
        }
        
        if (empty($fees)) {
            return 'Sin comisiones';
        }
        
        return implode(' + ', $fees);
    }

    /**
     * Get formatted amount limits.
     */
    public function getFormattedLimits(): string
    {
        $limits = [];
        
        if ($this->min_amount) {
            $limits[] = "Min: $" . number_format($this->min_amount, 2);
        }
        
        if ($this->max_amount) {
            $limits[] = "Max: $" . number_format($this->max_amount, 2);
        }
        
        return implode(' | ', $limits);
    }

    /**
     * Get provider configuration value.
     */
    public function getProviderConfig(string $key, mixed $default = null): mixed
    {
        return data_get($this->provider_config, $key, $default);
    }

    /**
     * Get configuration value.
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        return data_get($this->config, $key, $default);
    }
}
