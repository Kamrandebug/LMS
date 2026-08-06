<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\QuestionSet;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportMcqsFromJson extends Command
{
    protected $signature = 'mcq:import {--fresh : Truncate and re-import all data}';
    protected $description = 'Import MCQs from JSON files in storage/mcq-data/';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            if ($this->confirm('This will delete all existing subjects, topics, sets, and questions. Continue?')) {
                $this->warn('Truncating existing data...');
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                Question::truncate();
                QuestionSet::truncate();
                Topic::truncate();
                Subject::truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                $this->info('Existing data cleared.');
            } else {
                $this->info('Import cancelled.');
                return Command::SUCCESS;
            }
        }

        $files = glob(storage_path('mcq-data/*.json'));

        if (empty($files)) {
            $this->warn('No JSON files found in storage/mcq-data/');
            $this->info('Tip: Run php artisan db:seed --class=SubjectSeeder first to seed data programmatically.');
            return Command::SUCCESS;
        }

        $imported = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($files as $filePath) {
            $filename = basename($filePath);
            $this->info("Processing: {$filename}");

            try {
                $data = json_decode(file_get_contents($filePath), true, 512, JSON_THROW_ON_ERROR);

                DB::transaction(function () use ($data, &$imported, &$skipped) {
                    // Subject
                    $subjectData = $data['subject'];
                    $subject = Subject::updateOrCreate(
                        ['slug' => $subjectData['slug']],
                        [
                            'name' => $subjectData['name'],
                            'description' => $subjectData['description'] ?? '',
                            'is_active' => true,
                        ]
                    );
                    $this->line("  Subject: {$subject->name}");

                    // Topics
                    foreach ($data['topics'] as $topicData) {
                        $topic = Topic::updateOrCreate(
                            ['subject_id' => $subject->id, 'slug' => $topicData['slug']],
                            [
                                'name' => $topicData['name'],
                                'description' => $topicData['description'] ?? '',
                                'sort_order' => $topicData['sort_order'] ?? 0,
                                'is_active' => true,
                            ]
                        );
                        $this->line("  Topic: {$topic->name}");

                        // Sets
                        foreach ($topicData['sets'] as $setData) {
                            $set = QuestionSet::updateOrCreate(
                                ['topic_id' => $topic->id, 'set_number' => $setData['set_number']],
                                [
                                    'name' => $setData['name'] ?? "Set {$setData['set_number']}",
                                    'slug' => $setData['slug'] ?? "{$subject->slug}-{$topic->slug}-set-{$setData['set_number']}",
                                    'question_count' => count($setData['questions']),
                                    'is_active' => true,
                                ]
                            );

                            // Questions
                            foreach ($setData['questions'] as $qData) {
                                Question::updateOrCreate(
                                    ['qid' => $qData['qid']],
                                    [
                                        'question_set_id' => $set->id,
                                        'question' => $qData['question'],
                                        'option_a' => $qData['option_a'],
                                        'option_b' => $qData['option_b'],
                                        'option_c' => $qData['option_c'],
                                        'option_d' => $qData['option_d'],
                                        'correct_option' => $qData['correct_option'],
                                        'explanation' => $qData['explanation'] ?? '',
                                        'sort_order' => $qData['sort_order'] ?? 0,
                                        'is_active' => true,
                                    ]
                                );
                                $imported++;
                            }
                        }
                    }
                });

                $this->info("  ✓ Imported successfully");
            } catch (\Exception $e) {
                $this->error("  ✗ Failed: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->newLine();
        $this->table(
            ['Metric', 'Count'],
            [
                ['Files processed', count($files)],
                ['Questions imported/updated', $imported],
                ['Skipped', $skipped],
                ['Failed', $failed],
            ]
        );

        return Command::SUCCESS;
    }
}
