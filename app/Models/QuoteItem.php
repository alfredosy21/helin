<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class QuoteItem
 * Represents an individual product within a specific quotation.
 */
class QuoteItem extends Model {

    protected $fillable = [
        'quote_id',
        'product_id',
        'quantity',
        'unit_price'
    ];

    /**
     * Get the quote that owns the item.
     * * @return BelongsTo
     */
    public function quote(): BelongsTo {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Get the product details for this item.
     * * @return BelongsTo
     */
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
