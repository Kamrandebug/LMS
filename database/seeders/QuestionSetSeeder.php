<?php

namespace Database\Seeders;

use App\Models\QuestionSet;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class QuestionSetSeeder extends Seeder
{
    public function run(): void
    {
        $topics = Topic::with('subject')->get();

        foreach ($topics as $topic) {
            $subjectSlug = $topic->subject->slug;
            $topicSlug = $topic->slug;

            for ($setNum = 1; $setNum <= 10; $setNum++) {
                QuestionSet::create([
                    'topic_id' => $topic->id,
                    'name' => "{$topic->name} — Set {$setNum}",
                    'slug' => "{$subjectSlug}-{$topicSlug}-set-{$setNum}",
                    'set_number' => $setNum,
                    'question_count' => 20,
                    'is_active' => true,
                ]);
            }
        }
    }
}
