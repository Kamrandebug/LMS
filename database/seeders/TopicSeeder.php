<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            'computer-science' => [
                ['name' => 'Computer Fundamentals & Hardware', 'slug' => 'computer-fundamentals-hardware', 'sort_order' => 1],
                ['name' => 'Software & Programming Applications', 'slug' => 'software-programming-applications', 'sort_order' => 2],
                ['name' => 'Networking, Databases, Security & Emerging Tech', 'slug' => 'networking-databases-security-emerging', 'sort_order' => 3],
            ],
            'everyday-science' => [
                ['name' => 'Biology & Human Body', 'slug' => 'biology-human-body', 'sort_order' => 1],
                ['name' => 'Earth, Space & Environmental Science', 'slug' => 'earth-space-environment', 'sort_order' => 2],
                ['name' => 'Physics & Chemistry', 'slug' => 'physics-chemistry', 'sort_order' => 3],
            ],
            'general-knowledge' => [
                ['name' => 'World Geography', 'slug' => 'world-geography', 'sort_order' => 1],
                ['name' => 'World History & International Organizations', 'slug' => 'world-history-international', 'sort_order' => 2],
                ['name' => 'Sports, Science, Awards & Culture', 'slug' => 'sports-science-awards-culture', 'sort_order' => 3],
            ],
            'islamic-studies' => [
                ['name' => 'Islamic Culture & Society', 'slug' => 'islamic-culture-society', 'sort_order' => 1],
                ['name' => 'Prophets & Islamic History', 'slug' => 'prophets-islamic-history', 'sort_order' => 2],
                ['name' => 'Quran, Hadith & Beliefs', 'slug' => 'quran-hadith-beliefs', 'sort_order' => 3],
            ],
            'pakistan-studies' => [
                ['name' => 'Constitutional History & Politics', 'slug' => 'constitutional-history-politics', 'sort_order' => 1],
                ['name' => 'Culture, Heritage & Society', 'slug' => 'culture-heritage-society', 'sort_order' => 2],
                ['name' => 'Geography & Environment', 'slug' => 'geography-environment', 'sort_order' => 3],
            ],
            'english' => [
                ['name' => 'Analogies', 'slug' => 'analogies', 'sort_order' => 1],
                ['name' => 'Antonyms', 'slug' => 'antonyms', 'sort_order' => 2],
                ['name' => 'Conceptual Vocabulary', 'slug' => 'conceptual-vocabulary', 'sort_order' => 3],
                ['name' => 'Conditionals', 'slug' => 'conditionals', 'sort_order' => 4],
                ['name' => 'Functions', 'slug' => 'functions', 'sort_order' => 5],
                ['name' => 'Parts of Speech', 'slug' => 'parts-of-speech', 'sort_order' => 6],
                ['name' => 'Prefixes', 'slug' => 'prefixes', 'sort_order' => 7],
                ['name' => 'Suffixes', 'slug' => 'suffixes', 'sort_order' => 8],
            ],
        ];

        foreach ($topics as $subjectSlug => $topicList) {
            $subject = Subject::where('slug', $subjectSlug)->first();
            if (!$subject) continue;

            foreach ($topicList as $data) {
                Topic::create([
                    'subject_id' => $subject->id,
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'sort_order' => $data['sort_order'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
