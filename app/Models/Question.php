<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Question extends Model
{
    protected $fillable = [
        'question_set_id', 'qid', 'question',
        'option_a', 'option_b', 'option_c', 'option_d',
        'correct_option', 'explanation', 'sort_order', 'is_active'
    ];
    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (Question $question) {
            if (blank($question->qid)) {
                $question->qid = $question->generateQid();
            }
        });

        static::updating(function (Question $question) {
            if (blank($question->qid)) {
                $question->qid = $question->generateQid();
            }
        });
    }

    /**
     * Build a qid following the seeder convention:
     * {subject-slug}-{topic-slug}-s{set-number}-q{N}
     * Falls back to a unique short id if the set's chain can't be resolved.
     */
    protected function generateQid(): string
    {
        $set    = $this->questionSet()->first();
        $topic  = $set?->topic()->first();
        $subject = $topic?->subject()->first();

        $parts = array_filter([
            $subject?->slug, $topic?->slug,
            $set ? "s{$set->set_number}" : null,
            'q' . (static::where('question_set_id', $this->question_set_id)->count() + 1),
        ]);

        $base = $parts !== [] ? implode('-', $parts) : 'q';
        $base = Str::slug($base);

        // Ensure uniqueness against the unique column constraint.
        $qid = $base;
        $i   = 1;
        while (static::where('qid', $qid)->exists()) {
            $qid = $base . '-' . ++$i;
        }

        return $qid;
    }

    public function questionSet(): BelongsTo
    {
        return $this->belongsTo(QuestionSet::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(QuestionReport::class);
    }

    public function getCorrectAnswerTextAttribute(): string
    {
        return match($this->correct_option) {
            'a' => $this->option_a,
            'b' => $this->option_b,
            'c' => $this->option_c,
            'd' => $this->option_d,
            default => '',
        };
    }

    public function getOptionsArrayAttribute(): array
    {
        return [
            'a' => $this->option_a,
            'b' => $this->option_b,
            'c' => $this->option_c,
            'd' => $this->option_d,
        ];
    }

    public function getCorrectOptionIndexAttribute(): int
    {
        return match($this->correct_option) {
            'a' => 0,
            'b' => 1,
            'c' => 2,
            'd' => 3,
            default => -1,
        };
    }
}
