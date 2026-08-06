<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Playlist extends Model
{
    protected $fillable = [
        'name', 'slug', 'exam_name', 'exam_year',
        'description', 'target_audience', 'is_active', 'sort_order'
    ];
    protected $casts = ['is_active' => 'boolean'];

    protected function casts(): array
    {
        return [
            'exam_year' => 'integer',
        ];
    }

    public function modules(): HasMany
    {
        return $this->hasMany(PlaylistModule::class)->orderBy('module_number');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
