<?php

namespace Database\Seeders;

use App\Models\MockExam;
use Illuminate\Database\Seeder;

class MockExamSeeder extends Seeder
{
    public function run(): void
    {
        $exams = [
            [
                'name' => 'Mock Test 1',
                'slug' => 'mock-test-1',
                'exam_type' => 'fia',
                'total_questions' => 100,
                'duration_minutes' => 90,
                'negative_marking_value' => 0.25,
                'has_negative_marking' => true,
                'attempt_count' => 2120,
            ],
            [
                'name' => 'Mock Test 2',
                'slug' => 'mock-test-2',
                'exam_type' => 'fia',
                'total_questions' => 100,
                'duration_minutes' => 90,
                'negative_marking_value' => 0.25,
                'has_negative_marking' => true,
                'attempt_count' => 1850,
            ],
            [
                'name' => 'Mock Test 3',
                'slug' => 'mock-test-3',
                'exam_type' => 'ppsc',
                'total_questions' => 100,
                'duration_minutes' => 90,
                'negative_marking_value' => 0.25,
                'has_negative_marking' => true,
                'attempt_count' => 1560,
            ],
            [
                'name' => 'Mock Test 4',
                'slug' => 'mock-test-4',
                'exam_type' => 'ppsc',
                'total_questions' => 100,
                'duration_minutes' => 90,
                'negative_marking_value' => 0.25,
                'has_negative_marking' => true,
                'attempt_count' => 1320,
            ],
            [
                'name' => 'Mock Test 5',
                'slug' => 'mock-test-5',
                'exam_type' => 'ppsc',
                'total_questions' => 100,
                'duration_minutes' => 90,
                'negative_marking_value' => 0.25,
                'has_negative_marking' => true,
                'attempt_count' => 1180,
            ],
        ];

        foreach ($exams as $data) {
            MockExam::create($data);
        }
    }
}
