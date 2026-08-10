<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecordedLecture extends Model
{
    protected $fillable = [
        'topic_id', 'title', 'description', 'video_url', 'platform',
        'duration_minutes', 'sort_order', 'is_active',
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
     * Convert a YouTube watch URL to an embeddable src URL.
     * Returns video_url as-is for non-YouTube or unrecognized formats.
     */
    public function getEmbedUrl(): string
    {
        if ($this->platform === 'youtube') {
            preg_match('/(?:v=|\/)([a-zA-Z0-9_-]{11})/', $this->video_url, $matches);
            if (!empty($matches[1])) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        }
        return $this->video_url;
    }
}
