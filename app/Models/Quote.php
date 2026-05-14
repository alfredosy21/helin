<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Quote
 * Manages the quotation requests that act as orders in the commercial platform.
 */
class Quote extends Model
{
    protected $fillable = [
        'reference_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'specialty',
        'notes',
        'status'
    ];

    /**
     * Get the items associated with the quote.
     * * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }
}