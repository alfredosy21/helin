<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('name', 'asc');
    }
}
