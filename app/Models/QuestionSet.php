<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionSet extends Model
{
    protected $fillable = ['topic_id', 'name', 'slug', 'set_number', 'question_count', 'is_active', 'sort_order'];
    protected $casts = ['is_active' => 'boolean'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('sort_order');
    }

    public function playlistModules(): HasMany
    {
        return $this->hasMany(PlaylistModule::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getSetLabelAttribute(): string
    {
        return "Set {$this->set_number}";
    }
}
