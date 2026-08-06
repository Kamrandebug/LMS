<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtractFromBackup extends Command
{
    protected $signature = 'backup:extract {--fresh : Delete existing JSON files before extracting}';
    protected $description = 'Extract MCQ data from Backup/ folder → storage/mcq-data/ → import to DB';

    // ── Subject metadata ────────────────────────────────────────────────────────
    private array $subjectMeta = [
        'computer-science' => [
            'name'        => 'Computer Science',
            'description' => 'Master computing concepts for aptitude exams',
            'icon_svg'    => '💻',
            'color_class' => 'from-blue-500 via-cyan-500 to-teal-500',
            'sort_order'  => 1,
        ],
        'pakistan-studies' => [
            'name'        => 'Pakistan Studies',
            'description' => 'History, geography and politics of Pakistan',
            'icon_svg'    => '🇵🇰',
            'color_class' => 'from-green-600 via-emerald-500 to-teal-400',
            'sort_order'  => 2,
        ],
        'islamic-studies' => [
            'name'        => 'Islamic Studies',
            'description' => 'Quran, Hadith and Islamic history',
            'icon_svg'    => '☪️',
            'color_class' => 'from-emerald-600 via-green-500 to-lime-400',
            'sort_order'  => 3,
        ],
        'general-knowledge' => [
            'name'        => 'General Knowledge',
            'description' => 'World affairs, geography and current events',
            'icon_svg'    => '🌍',
            'color_class' => 'from-amber-500 via-orange-500 to-red-500',
            'sort_order'  => 4,
        ],
        'everyday-science' => [
            'name'        => 'Everyday Science',
            'description' => 'Biology, physics, chemistry and earth science',
            'icon_svg'    => '🔬',
            'color_class' => 'from-purple-500 via-violet-500 to-indigo-500',
            'sort_order'  => 5,
        ],
        'english' => [
            'name'        => 'English',
            'description' => 'Grammar, vocabulary and language skills',
            'icon_svg'    => '📖',
            'color_class' => 'from-pink-500 via-rose-500 to-red-400',
            'sort_order'  => 6,
        ],
    ];

    // ── Topic display names ──────────────────────────────────────────────────────
    private array $topicNames = [
        'computer-fundamentals-hardware'           => 'Computer Fundamentals & Hardware',
        'software-programming-applications'        => 'Software, Programming & Applications',
        'networking-databases-security-emerging'   => 'Networking, Databases, Security & Emerging Tech',
        'constitutional-history-politics'          => 'Constitutional History & Politics',
        'culture-heritage-society'                 => 'Culture, Heritage & Society',
        'geography-environment'                    => 'Geography & Environment',
        'islamic-culture-society'                  => 'Islamic Culture & Society',
        'prophets-islamic-history'                 => 'Prophets & Islamic History',
        'quran-hadith-beliefs'                     => 'Quran, Hadith & Beliefs',
        'world-history-international'              => 'World History & International Affairs',
        'world-geography'                          => 'World Geography',
        'sports-science-awards-culture'            => 'Sports, Science, Awards & Culture',
        'biology-human-body'                       => 'Biology & Human Body',
        'physics-chemistry'                        => 'Physics & Chemistry',
        'earth-space-environment'                  => 'Earth, Space & Environment',
        'analogies'                                => 'Analogies',
        'antonyms'                                 => 'Antonyms',
        'conceptual-vocabulary'                    => 'Conceptual Vocabulary',
        'conditionals'                             => 'Conditionals',
        'functions'                                => 'Functions',
        'parts-of-speech'                          => 'Parts of Speech',
        'prefixes'                                 => 'Prefixes',
        'suffixes'                                 => 'Suffixes',
    ];

    // ── Mock exam metadata ───────────────────────────────────────────────────────
    private array $mockMeta = [
        'fia-mock-exam-1' => [
            'name'                   => 'FIA Mock Exam 1',
            'exam_type'              => 'fia',
            'duration_minutes'       => 90,
            'negative_marking_value' => 0.25,
            'has_negative_marking'   => true,
        ],
        'fia-mock-exam-2' => [
            'name'                   => 'FIA Mock Exam 2',
            'exam_type'              => 'fia',
            'duration_minutes'       => 90,
            'negative_marking_value' => 0.25,
            'has_negative_marking'   => true,
        ],
        'deputy-accountant-3' => [
            'name'                   => 'Deputy Accountant Mock Exam 3',
            'exam_type'              => 'ppsc',
            'duration_minutes'       => 100,
            'negative_marking_value' => 0.25,
            'has_negative_marking'   => true,
        ],
        'deputy-accountant-4' => [
            'name'                   => 'Deputy Accountant Mock Exam 4',
            'exam_type'              => 'ppsc',
            'duration_minutes'       => 100,
            'negative_marking_value' => 0.25,
            'has_negative_marking'   => true,
        ],
        'deputy-accountant-5' => [
            'name'                   => 'Deputy Accountant Mock Exam 5',
            'exam_type'              => 'ppsc',
            'duration_minutes'       => 100,
            'negative_marking_value' => 0.25,
            'has_negative_marking'   => true,
        ],
    ];

    public function handle(): int
    {
        $backupBase  = base_path('Backup/mcqs.learnuppakistan.com');
        $subjectsOut = storage_path('mcq-data/subjects');
        $mocksOut    = storage_path('mcq-data/mock-exams');

        // ── Validate backup folder exists ────────────────────────────────────────
        if (! is_dir($backupBase)) {
            $this->error("Backup folder not found: {$backupBase}");
            $this->line('Place the unzipped backup folder at: ' . base_path('Backup/mcqs.learnuppakistan.com'));
            return self::FAILURE;
        }

        // ── Optional: wipe existing JSON ─────────────────────────────────────────
        if ($this->option('fresh')) {
            File::deleteDirectory($subjectsOut);
            File::deleteDirectory($mocksOut);
            foreach (array_keys($this->subjectMeta) as $slug) {
                File::delete(storage_path("mcq-data/{$slug}.json"));
            }
            $this->warn('Wiped existing JSON files.');
        }

        File::ensureDirectoryExists($subjectsOut);
        File::ensureDirectoryExists($mocksOut);

        // ────────────────────────────────────────────────────────────────────────
        // PHASE 1 — Extract subjects
        // ────────────────────────────────────────────────────────────────────────
        $this->info('');
        $this->info('═══════════════════════════════════════');
        $this->info(' PHASE 1: Extracting subject MCQs');
        $this->info('═══════════════════════════════════════');

        $totalQuestions = 0;

        foreach (array_keys($this->subjectMeta) as $subjectSlug) {
            $subjectDir = "{$backupBase}/{$subjectSlug}";
            if (! is_dir($subjectDir)) {
                $this->warn("  [SKIP] Subject folder not found: {$subjectSlug}");
                continue;
            }

            $meta   = $this->subjectMeta[$subjectSlug];
            $topics = [];
            $topicSortOrder = 0;

            // Iterate topic folders
            $topicDirs = array_filter(glob("{$subjectDir}/*"), 'is_dir');
            sort($topicDirs);

            foreach ($topicDirs as $topicDir) {
                $topicSlug = basename($topicDir);
                $topicName = $this->topicNames[$topicSlug]
                    ?? ucwords(str_replace('-', ' ', $topicSlug));

                $sets     = [];
                $setFiles = glob("{$topicDir}/set-*.html");

                if (empty($setFiles)) {
                    $this->warn("    [SKIP] No set files in: {$subjectSlug}/{$topicSlug}");
                    continue;
                }

                // Sort by set number numerically
                usort($setFiles, function ($a, $b) {
                    preg_match('/set-(\d+)\.html/', $a, $mA);
                    preg_match('/set-(\d+)\.html/', $b, $mB);
                    return (int) ($mA[1] ?? 0) <=> (int) ($mB[1] ?? 0);
                });

                foreach ($setFiles as $setFile) {
                    preg_match('/set-(\d+)\.html/', $setFile, $m);
                    $setNumber = (int) ($m[1] ?? 0);
                    if ($setNumber === 0) continue;

                    $html      = file_get_contents($setFile);
                    $rawQs     = $this->extractQuestions($html);

                    if (empty($rawQs)) {
                        $this->warn("    [SKIP] No questions in: {$subjectSlug}/{$topicSlug}/set-{$setNumber}.html");
                        continue;
                    }

                    $questions = array_map([$this, 'convertQuestion'], $rawQs);
                    $sets[]    = ['set_number' => $setNumber, 'questions' => $questions];
                    $totalQuestions += count($questions);
                }

                if (! empty($sets)) {
                    $topics[] = [
                        'name'       => $topicName,
                        'slug'       => $topicSlug,
                        'sort_order' => ++$topicSortOrder,
                        'sets'       => $sets,
                    ];
                }
            }

            if (empty($topics)) {
                $this->warn("  [SKIP] No topics extracted for: {$subjectSlug}");
                continue;
            }

            $payload = [
                'subject' => array_merge(['slug' => $subjectSlug], $meta),
                'topics'  => $topics,
            ];

            // Rich per-subject format (used by the home page / SubjectRepository)
            file_put_contents(
                "{$subjectsOut}/{$subjectSlug}.json",
                json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            // Top-level format (what mcq:import reads — expects a plain object)
            file_put_contents(
                storage_path("mcq-data/{$subjectSlug}.json"),
                json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            $qCount = array_sum(array_map(
                fn ($t) => array_sum(array_map(fn ($s) => count($s['questions']), $t['sets'])),
                $topics
            ));
            $this->info("  [OK] {$meta['name']} — " . count($topics) . " topics, {$qCount} questions");
        }

        $this->info("  Total MCQs extracted: {$totalQuestions}");

        // ────────────────────────────────────────────────────────────────────────
        // PHASE 2 — Extract mock exams
        // ────────────────────────────────────────────────────────────────────────
        $this->info('');
        $this->info('═══════════════════════════════════════');
        $this->info(' PHASE 2: Extracting mock exams');
        $this->info('═══════════════════════════════════════');

        $mockDir = "{$backupBase}/mock-papers";
        if (! is_dir($mockDir)) {
            $this->warn('  [SKIP] mock-papers folder not found, skipping.');
        } else {
            foreach ($this->mockMeta as $slug => $meta) {
                $htmlFile = "{$mockDir}/{$slug}.html";
                if (! file_exists($htmlFile)) {
                    $this->warn("  [SKIP] File not found: {$slug}.html");
                    continue;
                }

                $html  = file_get_contents($htmlFile);
                $rawQs = $this->extractQuestions($html);

                if (empty($rawQs)) {
                    $this->warn("  [SKIP] No questions in: {$slug}.html");
                    continue;
                }

                $questions = [];
                foreach ($rawQs as $i => $q) {
                    $conv = $this->convertQuestion($q);
                    $questions[] = [
                        'question_text'  => $conv['question'],
                        'option_a'       => $conv['option_a'],
                        'option_b'       => $conv['option_b'],
                        'option_c'       => $conv['option_c'],
                        'option_d'       => $conv['option_d'],
                        'correct_option' => $conv['correct_option'],
                        'explanation'    => $conv['explanation'],
                        'sort_order'     => $i + 1,
                    ];
                }

                $payload = array_merge(
                    ['slug' => $slug, 'total_questions' => count($questions)],
                    $meta,
                    ['questions' => $questions]
                );

                $outFile = "{$mocksOut}/{$slug}.json";
                file_put_contents($outFile, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $this->info("  [OK] {$meta['name']} — " . count($questions) . " questions");
            }
        }

        // ────────────────────────────────────────────────────────────────────────
        // PHASE 3 — Import into database
        // ────────────────────────────────────────────────────────────────────────
        $this->info('');
        $this->info('═══════════════════════════════════════');
        $this->info(' PHASE 3: Importing into database');
        $this->info('═══════════════════════════════════════');

        $this->info('  Running: mcq:import ...');
        $this->call('mcq:import');

        $this->info('  Running: mock:import ...');
        $this->call('mock:import');

        $this->info('');
        $this->info('✅  All done! Verify with:');
        $this->line('    php artisan tinker --execute="echo App\\Models\\Subject::count() . \' subjects, \' . App\\Models\\Question::count() . \' questions, \' . App\\Models\\MockExam::count() . \' mock exams\';"');

        return self::SUCCESS;
    }

    // ── Extract questions array from HTML (supports both variable names) ────────
    private function extractQuestions(string $html): array
    {
        foreach (['let activeQuestions = [', 'const questions = ['] as $marker) {
            $start = strpos($html, $marker);
            if ($start === false) continue;

            $start += strlen($marker) - 1; // point to opening '['
            $depth = 0;
            $i     = $start;
            $end   = $start;
            $len   = strlen($html);

            while ($i < $len) {
                if ($html[$i] === '[') {
                    $depth++;
                } elseif ($html[$i] === ']') {
                    $depth--;
                    if ($depth === 0) {
                        $end = $i;
                        break;
                    }
                }
                $i++;
            }

            if ($depth !== 0) continue; // malformed, try next marker

            $json   = substr($html, $start, $end - $start + 1);
            $result = json_decode($json, true);

            if (is_array($result)) {
                return $result;
            }
        }

        return [];
    }

    // ── Convert old format → new format ─────────────────────────────────────────
    private function convertQuestion(array $q): array
    {
        $map = [0 => 'a', 1 => 'b', 2 => 'c', 3 => 'd'];

        return [
            'qid'            => $q['qid'] ?? ($q['id'] ?? ''),
            'question'       => $q['question']      ?? '',
            'option_a'       => $q['options'][0]    ?? '',
            'option_b'       => $q['options'][1]    ?? '',
            'option_c'       => $q['options'][2]    ?? '',
            'option_d'       => $q['options'][3]    ?? '',
            'correct_option' => $map[$q['answer'] ?? 0] ?? 'a',
            'explanation'    => $q['explanation']   ?? '',
        ];
    }
}
