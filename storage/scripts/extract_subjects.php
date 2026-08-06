<?php

/**
 * Extract subject MCQ sets from the original HTML backup.
 *
 * Data source: Backup/mcqs.learnuppakistan.com/<subject>/<topic>/set-N.html
 * Pattern detected (Step 2): Pattern C — data attributes on HTML elements.
 *   - <div class="quiz-card" id="card-N" data-correct="0|1|2|3">  (0-indexed)
 *   - <h2 class="text-xl ..."> Question text </h2>
 *   - <button class="option-btn"> <span> A </span> Option text </button>  (A/B/C/D)
 *   - <div id="feedback-N"> ... <p class="text-blue-700"> Explanation </p> </div>
 *
 * Output: storage/mcq-data/subjects/{subject-slug}.json
 */

declare(strict_types=1);

const BACKUP_ROOT = __DIR__ . '/../../Backup/mcqs.learnuppakistan.com';
const OUTPUT_DIR  = __DIR__ . '/../mcq-data/subjects';

// Subject metadata captured from the original backup home page (index.html).
const SUBJECT_META = [
    'computer-science'   => ['name' => 'Computer Science',    'icon' => '💻', 'color' => 'from-cyan-500 via-blue-500 to-indigo-600',       'description' => 'Computer Science MCQs for LAT, PPSC, FPSC — computer fundamentals, programming, networking, databases and emerging tech.'],
    'everyday-science'   => ['name' => 'Everyday Science',    'icon' => '🧬', 'color' => 'from-amber-400 via-orange-500 to-red-500',       'description' => 'Everyday Science MCQs for LAT, PPSC, FPSC — physics, chemistry, biology, human body, earth and space.'],
    'general-knowledge'  => ['name' => 'General Knowledge',   'icon' => '🌍', 'color' => 'from-fuchsia-600 via-purple-600 to-indigo-800', 'description' => 'General Knowledge MCQs for LAT, PPSC, FPSC — world geography, world history and international affairs.'],
    'islamic-studies'    => ['name' => 'Islamic Studies',     'icon' => '🕌', 'color' => 'from-teal-400 via-emerald-400 to-green-500',    'description' => 'Islamic Studies MCQs for LAT, PPSC, FPSC — Quran, Hadith, beliefs, prophets and Islamic history.'],
    'pakistan-studies'   => ['name' => 'Pakistan Studies',    'icon' => '🇵🇰', 'color' => 'from-green-700 via-green-600 to-emerald-700',   'description' => 'Pakistan Studies MCQs for LAT, PPSC, FPSC — constitutional history, politics, culture, heritage and geography.'],
    'english'            => ['name' => 'English',             'icon' => '🧠', 'color' => 'from-violet-500 via-purple-500 to-pink-500',     'description' => 'English MCQs for LAT, PPSC, FPSC — parts of speech, vocabulary, prefixes, suffixes, analogies and grammar.'],
];

// Fallback colors per prompt for any subject not in the backup.
const FALLBACK_COLORS = [
    'computer-science' => 'from-blue-500 to-cyan-500',
    'everyday-science' => 'from-green-500 to-teal-500',
    'general-knowledge' => 'from-yellow-500 to-orange-500',
    'islamic-studies' => 'from-emerald-500 to-green-600',
    'pakistan-studies' => 'from-green-600 to-green-800',
    'english' => 'from-purple-500 to-violet-600',
    'mathematics' => 'from-red-500 to-pink-500',
    'math' => 'from-red-500 to-pink-500',
];

const FALLBACK_ICON = '📘';

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
 * Extract text of a DOM node, dropping any nested <span> text (option letters, Q-number prefixes).
 */
function textWithoutSpans(DOMElement $node): string
{
    $clone = $node->cloneNode(true);
    $spans = $clone->getElementsByTagName('span');
    for ($i = $spans->length - 1; $i >= 0; $i--) {
        $spans->item($i)->parentNode?->removeChild($spans->item($i));
    }
    return clean($clone->textContent);
}

/**
 * Parse one set HTML file into an array of normalized questions.
 *
 * @return list<array{question: string, option_a: string, option_b: string, option_c: string, option_d: string, correct_option: string, explanation: string}>
 */
function parseSetFile(string $file): array
{
    $html = file_get_contents($file);
    if ($html === false || $html === '') {
        return [];
    }

    // Reject obvious 404 pages that HTTrack captured.
    if (str_contains($html, '404 Not Found') || !str_contains($html, 'quiz-card')) {
        return [];
    }

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_COMPACT);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $cards = $xpath->query("//div[contains(concat(' ', normalize-space(@class), ' '), ' quiz-card ')]");
    if ($cards === false || $cards->length === 0) {
        return [];
    }

    $questions = [];

    foreach ($cards as $card) {
        /** @var DOMElement $card */
        $correctIdx = trim((string) $card->getAttribute('data-correct'));

        // Question text
        $h2 = $xpath->query('.//h2', $card)->item(0);
        $question = $h2 ? textWithoutSpans($h2) : '';
        if ($question === '') {
            continue;
        }

        // Options — each button.option-btn holds the letter in a <span> followed by the text.
        $options = [];
        $buttons = $xpath->query(".//button[contains(concat(' ', normalize-space(@class), ' '), ' option-btn ')]", $card);
        if ($buttons !== false) {
            foreach ($buttons as $btn) {
                $options[] = textWithoutSpans($btn);
            }
        }

        if (count($options) < 2) {
            continue; // malformed card — skip
        }

        // Pad options to 4 (some sets may have fewer; use last as filler if needed)
        while (count($options) < 4) {
            $options[] = $options[count($options) - 1] ?? '';
        }

        $correct = in_array($correctIdx, ['0', '1', '2', '3'], true) ? ['a', 'b', 'c', 'd'][(int) $correctIdx] : 'a';

        // Explanation
        $explanation = '';
        $feedback = $xpath->query('.//div[contains(@id, "feedback")]', $card)->item(0);
        if ($feedback) {
            $p = $xpath->query('.//p', $feedback)->item(0);
            $explanation = $p ? clean($p->textContent) : '';
        }

        $questions[] = [
            'question'       => $question,
            'option_a'       => $options[0],
            'option_b'       => $options[1],
            'option_c'       => $options[2],
            'option_d'       => $options[3],
            'correct_option' => $correct,
            'explanation'    => $explanation,
        ];
    }

    return $questions;
}

/**
 * Pretty-print a folder slug into a topic display name.
 */
function prettifySlug(string $slug): string
{
    $slug = str_replace('_', ' ', $slug);
    $words = preg_split('/[\s\-]+/', $slug);
    $title = implode(' ', array_map('ucfirst', $words ?: []));
    return trim($title);
}

/**
 * Explicit topic display names captured from the original backup (subject page topic cards).
 */
const TOPIC_NAME_OVERRIDES = [
    'computer-fundamentals-hardware'       => 'Computer Fundamentals & Hardware',
    'networking-databases-security-emerging' => 'Networking, Databases, Security & Emerging Tech',
    'software-programming-applications'    => 'Software & Programming Applications',
];

/**
 * Parse a set number out of a filename: set-1.html, set1.html, s1.html → 1.
 */
function parseSetNumber(string $filename): ?int
{
    if (preg_match('/set[-\s]?(\d+)/i', $filename, $m)) {
        return (int) $m[1];
    }
    if (preg_match('/^s(\d+)/i', $filename, $m)) {
        return (int) $m[1];
    }
    return null;
}

// ---------------------------------------------------------------- main

if (!is_dir(OUTPUT_DIR)) {
    mkdir(OUTPUT_DIR, 0777, true);
}

$subjectDirs = glob(BACKUP_ROOT . '/*', GLOB_ONLYDIR) ?: [];

$summary = [];

foreach ($subjectDirs as $subjectDir) {
    $subjectSlug = basename($subjectDir);

    // Only process known subject folders that contain topic subfolders.
    if (!isset(SUBJECT_META[$subjectSlug])) {
        continue;
    }

    $meta = SUBJECT_META[$subjectSlug];

    $topicDirs = glob($subjectDir . '/*', GLOB_ONLYDIR) ?: [];
    $topics = [];
    $topicIndex = 0;

    foreach ($topicDirs as $topicDir) {
        $topicSlug = basename($topicDir);
        $topicName = TOPIC_NAME_OVERRIDES[$topicSlug] ?? prettifySlug($topicSlug);
        $topicIndex++;

        $setFiles = glob($topicDir . '/*.html') ?: [];
        $sets = [];

        foreach ($setFiles as $setFile) {
            $filename = basename($setFile);
            $setNumber = parseSetNumber($filename);
            if ($setNumber === null) {
                continue; // skip 404 pages / non-set files
            }

            $questions = parseSetFile($setFile);
            if (empty($questions)) {
                continue;
            }

            $normalized = [];
            foreach ($questions as $i => $q) {
                $qNum = $i + 1;
                $normalized[] = [
                    'qid'            => "{$subjectSlug}-{$topicSlug}-s{$setNumber}-q{$qNum}",
                    'question'       => $q['question'],
                    'option_a'       => $q['option_a'],
                    'option_b'       => $q['option_b'],
                    'option_c'       => $q['option_c'],
                    'option_d'       => $q['option_d'],
                    'correct_option' => $q['correct_option'],
                    'explanation'    => $q['explanation'],
                ];
            }

            $sets[] = [
                'set_number' => $setNumber,
                'questions'  => $normalized,
            ];
        }

        usort($sets, fn($a, $b) => $a['set_number'] <=> $b['set_number']);

        if (!empty($sets)) {
            $topics[] = [
                'name'       => $topicName,
                'slug'       => $topicSlug,
                'sort_order' => $topicIndex,
                'sets'       => $sets,
            ];
        }
    }

    if (empty($topics)) {
        $summary[$subjectSlug] = 'NO DATA';
        continue;
    }

    // Prefer the real gradient from the backup home page; fall back to the prompt defaults.
    $color = $meta['color'] ?? (FALLBACK_COLORS[$subjectSlug] ?? 'from-slate-500 to-slate-700');
    $icon = $meta['icon'] ?? FALLBACK_ICON;

    $payload = [
        'subject' => [
            'name'        => $meta['name'],
            'slug'        => $subjectSlug,
            'icon_svg'    => $icon,
            'color_class' => $color,
            'description' => $meta['description'],
        ],
        'topics' => $topics,
    ];

    $outFile = OUTPUT_DIR . '/' . $subjectSlug . '.json';
    file_put_contents($outFile, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    $setCount = array_sum(array_map(fn($t) => count($t['sets']), $topics));
    $qCount = array_sum(array_map(fn($t) => array_sum(array_map(fn($s) => count($s['questions']), $t['sets'])), $topics));

    $summary[$subjectSlug] = sprintf('%d topics, %d sets, %d questions', count($topics), $setCount, $qCount);
}

echo "=== SUBJECT EXTRACTION SUMMARY ===\n";
foreach ($summary as $slug => $line) {
    echo "✅ {$slug}.json — {$line}\n";
}
echo "\nDone.\n";
