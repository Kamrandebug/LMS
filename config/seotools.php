<?php

return [
    'meta' => [
        'defaults' => [
            'title' => 'LearnUp — Free MCQ Exam Preparation',
            'description' => 'Free MCQ practice platform for PPSC, FPSC, CSS, PMS, LAT. 3,600+ questions with explanations.',
            'separator' => ' | ',
            'keywords' => ['PPSC MCQs', 'FPSC MCQs', 'CSS preparation', 'LAT preparation', 'Pakistan MCQs', 'competitive exams Pakistan'],
            'canonical' => 'current',
        ],
    ],
    'opengraph' => [
        'defaults' => [
            'title' => 'LearnUp',
            'description' => 'Free MCQ Exam Preparation Platform',
            'type' => 'website',
            'url' => env('APP_URL', 'https://mcqs.learnup.com'),
            'site_name' => 'LearnUp',
            'images' => [env('APP_URL', 'https://mcqs.learnup.com') . '/images/og-default.png'],
        ],
    ],
    'twitter' => [
        'defaults' => [
            'card' => 'summary_large_image',
            'site' => '@learnup',
        ],
    ],
];
