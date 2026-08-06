<?php

namespace App\Console\Commands;

use App\Models\MockExam;
use App\Models\MockExamQuestion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportMockExamsFromJson extends Command
{
    protected $signature = 'mock:import {--fresh : Truncate all mock exam data before importing}';
    protected $description = 'Import mock exams from JSON files in storage/mcq-data/mock-exams/';

    public function handle(): int
    {
        $path = storage_path('mcq-data/mock-exams');

        if (!is_dir($path)) {
            $this->error("Directory not found: {$path}");
            return Command::FAILURE;
        }

        if ($this->option('fresh')) {
            $this->warn('Truncating mock exam data...');
            MockExamQuestion::truncate();
            MockExam::truncate();
            $this->info('Truncated.');
        }

        $files = glob("{$path}/*.json");

        if (empty($files)) {
            $this->error('No JSON files found in ' . $path);
            return Command::FAILURE;
        }

        $this->info('Found ' . count($files) . ' mock exam files.');
        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        $totalExams = 0;
        $totalQuestions = 0;
        $failed = [];

        foreach ($files as $file) {
            try {
                DB::transaction(function () use ($file, &$totalExams, &$totalQuestions) {
                    $data = json_decode(file_get_contents($file), true);

                    if (!$data || empty($data['questions'])) {
                        throw new \Exception("Invalid or empty JSON in {$file}");
                    }

                    $exam = MockExam::updateOrCreate(
                        ['slug' => $data['slug']],
                        [
                            'name'                   => $data['name'],
                            'exam_type'              => $data['exam_type'] ?? 'custom',
                            'total_questions'        => count($data['questions']),
                            'duration_minutes'       => $data['duration_minutes'] ?? 90,
                            'negative_marking_value' => $data['negative_marking_value'] ?? 0.25,
                            'has_negative_marking'   => $data['has_negative_marking'] ?? true,
                            'is_active'              => true,
                        ]
                    );

                    // Delete existing questions and re-insert
                    $exam->questions()->delete();

                    $questions = [];
                    foreach ($data['questions'] as $i => $q) {
                        $questions[] = [
                            'mock_exam_id'   => $exam->id,
                            'question_text'  => strip_tags($q['question_text']),
                            'option_a'       => strip_tags($q['option_a']),
                            'option_b'       => strip_tags($q['option_b']),
                            'option_c'       => strip_tags($q['option_c']),
                            'option_d'       => strip_tags($q['option_d']),
                            'correct_option' => strtolower(trim($q['correct_option'])),
                            'explanation'    => strip_tags($q['explanation'] ?? ''),
                            'sort_order'     => $i + 1,
                            'created_at'     => now(),
                            'updated_at'     => now(),
                        ];
                    }

                    MockExamQuestion::insert($questions);
                    $totalExams++;
                    $totalQuestions += count($questions);
                });
            } catch (\Throwable $e) {
                $failed[] = basename($file) . ': ' . $e->getMessage();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Imported {$totalExams} mock exams with {$totalQuestions} questions.");

        if (!empty($failed)) {
            $this->warn('Failed files:');
            foreach ($failed as $f) {
                $this->error("  - {$f}");
            }
        }

        return Command::SUCCESS;
    }
}
