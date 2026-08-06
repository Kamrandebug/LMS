<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Computer Science',
                'slug' => 'computer-science',
                'description' => 'Practice MCQs covering computer fundamentals, programming, networking, and emerging technologies.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16"><path d="M4 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4zm0 1h8v12H4V2zm1 2v1h6V4H5zm0 3v1h6V7H5zm5 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>',
                'color_class' => 'from-cyan-500 via-blue-500 to-indigo-600',
                'sort_order' => 1,
            ],
            [
                'name' => 'Everyday Science',
                'slug' => 'everyday-science',
                'description' => 'MCQs on biology, chemistry, physics, and environmental science for competitive exams.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16"><path d="M8.5 1.5A1.5 1.5 0 0 1 10 0h4a1.5 1.5 0 0 1 1.5 1.5v13A1.5 1.5 0 0 1 14 16h-4a1.5 1.5 0 0 1-1.5-1.5V13h-1v1.5A1.5 1.5 0 0 1 6 16H2a1.5 1.5 0 0 1-1.5-1.5v-13A1.5 1.5 0 0 1 2 0h4a1.5 1.5 0 0 1 1.5 1.5v1h1v-1z"/></svg>',
                'color_class' => 'from-emerald-500 via-teal-500 to-cyan-600',
                'sort_order' => 2,
            ],
            [
                'name' => 'General Knowledge',
                'slug' => 'general-knowledge',
                'description' => 'World geography, history, international organizations, sports, and current affairs.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM2.14 5.5h2.84c.12-1.07.38-2.08.75-2.97A6.03 6.03 0 0 0 2.14 5.5zM8 8.5H5.5c-.12-1.07-.38-2.08-.75-2.97A6.14 6.14 0 0 1 8 5.5v3zm0-4.5c-.78 0-1.48.42-2.13 1.03-.37-.9-.63-1.91-.75-3H8v1.97zm4.97 4.5H8v-3c.78 0 1.48.42 2.13 1.03.37-.9.63-1.91.75-3A6.14 6.14 0 0 1 12.97 8.5zm-1.06 0c-.12 1.07-.38 2.08-.75 2.97A6.14 6.14 0 0 1 8 11.5V8.5h3.91zM8 12.5c-.78 0-1.48-.42-2.13-1.03-.37.9-.63 1.91-.75 3H8v-1.97zm-3.44.97c-.37-.9-.63-1.91-.75-3H2.14a6.03 6.03 0 0 0 2.42 3zm7.3-3c-.12 1.07-.38 2.08-.75 3a6.03 6.03 0 0 0 2.42-3h-1.67z"/></svg>',
                'color_class' => 'from-amber-500 via-orange-500 to-red-600',
                'sort_order' => 3,
            ],
            [
                'name' => 'Islamic Studies',
                'slug' => 'islamic-studies',
                'description' => 'Islamic culture, history, prophets, Quran, Hadith, and beliefs MCQs.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16"><path d="M4 0h8v1H4V0zm0 2h8v1H4V2zm0 2h8v1H4V4zm0 2h5v1H4V6zm0 2h3v1H4V8zm0 2h2v1H4v-1zm0 2h1v1H4v-1z"/></svg>',
                'color_class' => 'from-emerald-600 via-green-600 to-teal-700',
                'sort_order' => 4,
            ],
            [
                'name' => 'Pakistan Studies',
                'slug' => 'pakistan-studies',
                'description' => 'Constitutional history, politics, culture, heritage, geography, and environment of Pakistan.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0z"/></svg>',
                'color_class' => 'from-green-600 via-green-500 to-emerald-600',
                'sort_order' => 5,
            ],
            [
                'name' => 'English',
                'slug' => 'english',
                'description' => 'English grammar, vocabulary, analogies, antonyms, conditionals, and parts of speech.',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16"><path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12z"/></svg>',
                'color_class' => 'from-purple-500 via-pink-500 to-rose-600',
                'sort_order' => 6,
            ],
        ];

        foreach ($subjects as $data) {
            Subject::create($data);
        }
    }
}
