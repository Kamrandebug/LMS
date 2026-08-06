<?php

namespace Database\Seeders;

use App\Models\Playlist;
use Illuminate\Database\Seeder;

class PlaylistSeeder extends Seeder
{
    public function run(): void
    {
        $playlists = [
            [
                'name' => 'CSS MPT Screening Test 2026',
                'slug' => 'css-mpt-2026',
                'exam_name' => 'CSS Screening Test',
                'exam_year' => 2026,
                'description' => 'The ultimate 200-mark practice marathon for the CSS Screening Test covering all core subjects.',
                'target_audience' => 'CSS aspirants',
                'sort_order' => 1,
            ],
            [
                'name' => 'PMS GK 2026',
                'slug' => 'pms-gk-2026',
                'exam_name' => 'PMS General Knowledge',
                'exam_year' => 2026,
                'description' => 'Comprehensive General Knowledge preparation for PMS examinations.',
                'target_audience' => 'PMS aspirants',
                'sort_order' => 2,
            ],
            [
                'name' => 'FIA AD 2026',
                'slug' => 'fia-ad-2026',
                'exam_name' => 'FIA Assistant Director',
                'exam_year' => 2026,
                'description' => 'Targeted preparation for FIA Assistant Director exam including all relevant subjects.',
                'target_audience' => 'FIA AD candidates',
                'sort_order' => 3,
            ],
            [
                'name' => 'MOD AD 2026',
                'slug' => 'mod-ad-2026',
                'exam_name' => 'MOD Assistant Director',
                'exam_year' => 2026,
                'description' => 'Complete preparation for Ministry of Defence Assistant Director exam.',
                'target_audience' => 'MOD candidates',
                'sort_order' => 4,
            ],
            [
                'name' => 'MOD Analyst 2026',
                'slug' => 'mod-analyst-2026',
                'exam_name' => 'MOD Analyst',
                'exam_year' => 2026,
                'description' => 'Comprehensive study plan for MOD Analyst position.',
                'target_audience' => 'MOD Analyst candidates',
                'sort_order' => 5,
            ],
            [
                'name' => 'MOD SI 2026',
                'slug' => 'mod-si-2026',
                'exam_name' => 'MOD Sub-Inspector',
                'exam_year' => 2026,
                'description' => 'Focused preparation for MOD Sub-Inspector exam.',
                'target_audience' => 'MOD SI candidates',
                'sort_order' => 6,
            ],
            [
                'name' => 'Customs Inspector FPSC',
                'slug' => 'customs-inspector-fpsc',
                'exam_name' => 'FPSC Customs Inspector',
                'description' => 'Multi-subject curriculum for FPSC Customs Inspector examination.',
                'target_audience' => 'Customs Inspector candidates',
                'sort_order' => 7,
            ],
            [
                'name' => 'PPSC Assistant (General)',
                'slug' => 'ppsc-assistant-general',
                'exam_name' => 'PPSC Assistant',
                'description' => 'General OnePaper preparation for PPSC Assistant exams.',
                'target_audience' => 'PPSC candidates',
                'sort_order' => 8,
            ],
            [
                'name' => 'Punjab Police SI',
                'slug' => 'punjab-police-si',
                'exam_name' => 'Punjab Police Sub-Inspector',
                'description' => 'Focused preparation for Punjab Police Sub-Inspector exam.',
                'target_audience' => 'Police SI candidates',
                'sort_order' => 9,
            ],
            [
                'name' => 'Tehsildar PPSC',
                'slug' => 'tehsildar-ppsc',
                'exam_name' => 'PPSC Tehsildar',
                'description' => 'Targeted preparation for PPSC Tehsildar examination.',
                'target_audience' => 'Tehsildar candidates',
                'sort_order' => 10,
            ],
            [
                'name' => 'ASF Assistant Director',
                'slug' => 'asf-assistant-director',
                'exam_name' => 'ASF Assistant Director',
                'description' => 'Complete preparation for ASF Assistant Director exam.',
                'target_audience' => 'ASF candidates',
                'sort_order' => 11,
            ],
            [
                'name' => 'ASF Inspector',
                'slug' => 'asf-inspector',
                'exam_name' => 'ASF Inspector',
                'description' => 'Comprehensive study plan for ASF Inspector position.',
                'target_audience' => 'ASF Inspector candidates',
                'sort_order' => 12,
            ],
            [
                'name' => 'ASF Test Preparation',
                'slug' => 'asf-test-preparation',
                'exam_name' => 'ASF General Test',
                'description' => 'Broad preparation for ASF general test.',
                'target_audience' => 'ASF candidates',
                'sort_order' => 13,
            ],
            [
                'name' => 'IB GD Preparation',
                'slug' => 'ib-gd-preparation',
                'exam_name' => 'IB General Duty',
                'description' => 'Focused preparation for Intelligence Bureau General Duty test.',
                'target_audience' => 'IB GD candidates',
                'sort_order' => 14,
            ],
            [
                'name' => 'LAT Preparation',
                'slug' => 'lat-preparation',
                'exam_name' => 'Law Admission Test',
                'description' => 'Essential preparation for Law Admission Test covering all subjects.',
                'target_audience' => 'LAT candidates',
                'sort_order' => 15,
            ],
        ];

        foreach ($playlists as $data) {
            Playlist::create($data);
        }
    }
}
