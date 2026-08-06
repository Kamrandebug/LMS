<?php

/**
 * Extract mock exam papers from the original HTML backup.
 *
 * Data source: Backup/mcqs.learnuppakistan.com/mock-papers/*.html
 * Pattern detected (Step 2): inline JS question array.
 *   const totalQuestions = 100;
 *   const durationMinutes = 90;
 *   const defaultNegativeMarking = 0.25;
 *   const paperTitle = 'Deputy Accountant Mock Exam 1';
 *   const STORAGE_KEY = 'mock_progress_' + 'fia-mock-exam-1';
 *   const questions = [{"id":1,"question":"...","options":["A","B","C","D"],"answer":2,"explanation":"..."}];
 *
 * The `answer` field is 0-indexed (0=a,1=b,2=c,3=d).
 *
 * Output: storage/mcq-data/mock-exams/{slug}.json
 */

declare(strict_types=1);

const MOCK_DIR = __DIR__ . '/../../Backup/mcqs.learnuppakistan.com/mock-papers';
const OUTPUT_DIR = __DIR__ . '/../mcq-data/mock-exams';

/**
 * Strip HTML and collapse whitespace.
 */
function clean(?string $text): string
{
    $text = (string) $text;
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = strip_tags($text);
    $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');
    return $text;
}

/**
 * Determine exam_type from file path / title.
 */
function detectExamType(string $file, string $title): string
{
    $haystack = strtolower(basename($file) . ' ' . $title);
    foreach (['fia', 'ppsc', 'fpsc', 'css', 'nts', 'lat'] as $type) {
        if (str_contains($haystack, $type)) {
            return $type;
        }
    }
    return 'custom';
}

/**
 * Extract the config constants and the questions JSON array from a mock exam file.
 *
 * @return array{slug: string, name: string, exam_type: string, total_questions: int, duration_minutes: int, negative_marking_value: float, has_negative_marking: bool, questions: array, failed?: string}|null
 */
function parseMockFile(string $file): ?array
{
    $html = file_get_contents($file);
    if ($html === false || $html === '') {
        return null;
    }

    // 1. Slug — from the STORAGE_KEY line: 'mock_progress_' + 'fia-mock-exam-1'
    $slug = null;
    if (preg_match("/mock_progress_\'\s*\+\s*'([^']+)'/", $html, $m)) {
        $slug = clean($m[1]);
    }
    if ($slug === null && preg_match("/mock_progress_'\+'([^']+)'/", $html, $m)) {
        $slug = clean($m[1]);
    }
    if ($slug === null) {
        $slug = basename($file, '.html');
    }

    // 2. Title — paperTitle constant, fall back to <title>
    $name = null;
    if (preg_match("/const\s+paperTitle\s*=\s*'([^']+)'/", $html, $m)) {
        $name = clean($m[1]);
    }
    if ($name === null && preg_match('/<title>\s*([^<]+?)\s*<\/title>/', $html, $m)) {
        $name = clean($m[1]);
    }
    $name = $name ?: ucwords(str_replace('-', ' ', $slug));

    // 3. Config constants
    $totalQuestions = (int) (preg_match('/const\s+totalQuestions\s*=\s*(\d+)/', $html, $m) ? $m[1] : 0);
    $duration = (int) (preg_match('/const\s+durationMinutes\s*=\s*(\d+)/', $html, $m) ? $m[1] : 90);
    $negMarking = (float) (preg_match('/const\s+defaultNegativeMarking\s*=\s*([\d.]+)/', $html, $m) ? $m[1] : 0.25);

    // 4. Questions JSON array
    $questions = [];
    if (preg_match('/const\s+questions\s*=\s*(\[.*?\])\s*;\s*let\s+userState/s', $html, $m)) {
        $decoded = json_decode($m[1], true);
        if (is_array($decoded)) {
            foreach ($decoded as $i => $q) {
                $options = $q['options'] ?? [];
                while (count($options) < 4) {
                    $options[] = '';
                }
                $answer = (int) ($q['answer'] ?? 0);
                $correct = in_array($answer, [0, 1, 2, 3], true)
                    ? ['a', 'b', 'c', 'd'][$answer]
                    : 'a';

                $questions[] = [
                    'question_text'   => clean($q['question'] ?? ''),
                    'option_a'        => clean($options[0] ?? ''),
                    'option_b'        => clean($options[1] ?? ''),
                    'option_c'        => clean($options[2] ?? ''),
                    'option_d'        => clean($options[3] ?? ''),
                    'correct_option'  => $correct,
                    'explanation'     => clean($q['explanation'] ?? ''),
                ];
            }
        }
    }

    $examType = detectExamType($file, $name);
    $actualCount = count($questions);
    $hasNegative = $negMarking > 0;

    if ($actualCount === 0) {
        return [
            'slug' => $slug,
            'name' => $name,
            'exam_type' => $examType,
            'total_questions' => $totalQuestions,
            'duration_minutes' => $duration,
            'negative_marking_value' => $negMarking,
            'has_negative_marking' => $hasNegative,
            'questions' => [],
            'failed' => 'No questions parsed',
        ];
    }

    return [
        'slug' => $slug,
        'name' => $name,
        'exam_type' => $examType,
        'total_questions' => $actualCount,
        'duration_minutes' => $duration,
        'negative_marking_value' => $negMarking,
        'has_negative_marking' => $hasNegative,
        'questions' => $questions,
    ];
}

// ---------------------------------------------------------------- main

if (!is_dir(OUTPUT_DIR)) {
    mkdir(OUTPUT_DIR, 0777, true);
}

$files = glob(MOCK_DIR . '/*.html') ?: [];
sort($files);

$processed = 0;
$totalQs = 0;
$failed = [];

foreach ($files as $file) {
    $result = parseMockFile($file);
    $slug = $result['slug'] ?? basename($file, '.html');

    if ($result === null || !empty($result['failed'])) {
        $failed[] = basename($file) . ': ' . ($result['failed'] ?? 'Unreadable file');
        echo "❌ Skipped: " . basename($file) . "\n";
        continue;
    }

    // Add sort_order to questions
    foreach ($result['questions'] as $i => &$q) {
        $q['sort_order'] = $i + 1;
    }
    unset($q);

    $outFile = OUTPUT_DIR . '/' . $slug . '.json';
    file_put_contents($outFile, json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    $processed++;
    $totalQs += count($result['questions']);
    echo "✅ Extracted: {$slug}.json (" . count($result['questions']) . " questions)\n";
}

echo "\n=== MOCK EXAM SUMMARY ===\n";
echo "Files processed: {$processed}, questions extracted: {$totalQs}\n";
if (!empty($failed)) {
    echo "Failed files:\n";
    foreach ($failed as $f) {
        echo "  - {$f}\n";
    }
}
echo "Done.\n";
