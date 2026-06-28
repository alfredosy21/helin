<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state_id',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('name', 'asc');
    }
}
