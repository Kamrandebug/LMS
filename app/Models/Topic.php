<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    protected $fillable = ['subject_id', 'name', 'slug', 'description', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function questionSets(): HasMany
    {
        return $this->hasMany(QuestionSet::class)->orderBy('set_number');
    }

    public function resourceMaterials(): HasMany
    {
        return $this->hasMany(ResourceMaterial::class)->orderBy('sort_order');
    }

    public function recordedLectures(): HasMany
    {
        return $this->hasMany(RecordedLecture::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
