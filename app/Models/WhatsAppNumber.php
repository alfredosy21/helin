<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppNumber extends Model
{
    protected $table = 'whatsapp_numbers';

    protected $fillable = [
        'phone_number',
        'executive_name',
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
        return static::whereHas('states', fn($q) => $q->where('states.id', $stateId))
            ->where('is_active', true)
            ->first();
    }

    /**
     * Relación muchos-a-muchos con estados
     */
    public function states()
    {
        return $this->belongsToMany(State::class, 'state_whatsapp_number', 'whatsapp_number_id', 'state_id');
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
