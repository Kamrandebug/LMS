<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceMaterial extends Model
{
    protected $fillable = [
        'topic_id', 'title', 'description', 'file_url', 'file_path', 'file_type',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the public URL for the stored file.
     * Falls back to file_url if no local file was uploaded.
     */
    public function getStorageUrl(): ?string
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return $this->file_url;
    }
}
