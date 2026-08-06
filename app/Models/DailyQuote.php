<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyQuote extends Model
{
    protected $fillable = ['quote', 'author', 'type', 'is_friday_special', 'is_active'];
    protected $casts = [
        'is_friday_special' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
