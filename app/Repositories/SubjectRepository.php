<?php

namespace App\Repositories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;

class SubjectRepository
{
    public function getAllActive(): Collection
    {
        return Subject::active()->with(['topics' => function ($q) {
            $q->active()->withCount('questionSets');
        }])->get();
    }

    public function getBySlug(string $slug): ?Subject
    {
        return Subject::where('slug', $slug)
            ->where('is_active', true)
            ->with(['topics' => function ($q) {
                $q->active()->with(['questionSets' => function ($q) {
                    $q->active()->orderBy('set_number');
                }]);
            }])
            ->first();
    }
}
