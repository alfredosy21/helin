<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Session Model
 *
 * Used only for administrative purposes or user profile
 * session management.
 */
class Session extends Model {

    // Laravel no usa 'id' incremental en sesiones, usa un string único
    public $incrementing = false;
    protected $keyType = 'string';
    // Desactivamos timestamps automáticos porque la tabla usa 'last_activity'
    public $timestamps = false;

    /**
     * Name of the table
     * @var type
     */
    protected $table = 'sessions';

    /**
     * Relationship with the User.
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
