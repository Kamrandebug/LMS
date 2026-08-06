<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SubjectSeeder::class,
            TopicSeeder::class,
            QuestionSetSeeder::class,
            QuestionSeeder::class,
            DailyQuoteSeeder::class,
            PlaylistSeeder::class,
            MockExamSeeder::class,
        ]);
    }
}
