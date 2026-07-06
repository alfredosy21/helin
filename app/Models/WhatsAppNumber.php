<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppNumber extends Model
{
    protected $fillable = [
        'phone_number',
        'state_id',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Obtener número de WhatsApp activo por estado
     */
    public static function getActiveByState(int $stateId): ?self
    {
        return static::where('state_id', $stateId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Relación con el estado
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Obtener URL de WhatsApp
     */
    public function getWhatsAppUrlAttribute(): string
    {
        return "https://wa.me/{$this->phone_number}";
    }

    /**
     * Obtener número formateado para mostrar
     */
    public function getFormattedNumberAttribute(): string
    {
        // Formato: +58 424-278-9481
        $number = $this->phone_number;
        if (strlen($number) === 11 && str_starts_with($number, '58')) {
            return '+58 ' . substr($number, 2, 3) . '-' . substr($number, 5, 3) . '-' . substr($number, 8);
        }
        return '+' . $number;
    }
}
