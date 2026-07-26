<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommercialRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'customer_type_id',
        'first_name',
        'last_name',
        'cedula',
        'company_name',
        'rif',
        'phone',
        'email',
        'state_id',
        'city_id',
        'address',
        'observations',
        'delivery_method_id',
        'other_delivery_company',
        'recipient_name',
        'recipient_document',
        'recipient_phone',
        'shipping_state_id',
        'shipping_city_id',
        'destination_agency',
        'payment_method_id',
        'payment_receipt_number',
        'whatsapp_number_id',
        'status',
        'cart_data',
        'privacy_accepted',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'privacy_accepted' => 'boolean',
    ];

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class);
    }

    public function shippingState()
    {
        return $this->belongsTo(State::class, 'shipping_state_id');
    }

    public function shippingCity()
    {
        return $this->belongsTo(City::class, 'shipping_city_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function whatsappNumber()
    {
        return $this->belongsTo(WhatsAppNumber::class);
    }

    /**
     * Asociar automáticamente el número de WhatsApp del estado
     */
    public function associateWhatsAppNumber(): void
    {
        if ($this->state_id) {
            $whatsappNumber = WhatsAppNumber::getActiveByState($this->state_id);
            if ($whatsappNumber) {
                $this->whatsapp_number_id = $whatsappNumber->id;
            }
        }
    }

    /**
     * Boot method para asociar WhatsApp automáticamente al guardar y generar UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            $request->uuid = (string) \Illuminate\Support\Str::uuid();
            $request->associateWhatsAppNumber();
        });

        static::updating(function ($request) {
            if ($request->isDirty('state_id')) {
                $request->associateWhatsAppNumber();
            }
        });
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getCorrelativeAttribute()
    {
        $whatsappId = $this->whatsapp_number_id ? 'Z' . $this->whatsapp_number_id : 'Z0';
        $requestId = str_pad($this->id, 2, '0', STR_PAD_LEFT);
        return "#HELIN-{$whatsappId}-{$requestId}";
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
