<?php

return [
    'adsense' => [
        'client_id' => env('ADSENSE_CLIENT_ID', 'ca-pub-XXXXXXXXXXXXXXXX'),
        'slots' => [
            'between_questions' => env('ADSENSE_SLOT_BETWEEN', 'XXXXXXXXXX'),
            'result_page' => env('ADSENSE_SLOT_RESULT', 'XXXXXXXXXX'),
            'playlist_sidebar' => env('ADSENSE_SLOT_PLAYLIST', 'XXXXXXXXXX'),
        ],
    ],
    'whatsapp' => [
        'share_base_url' => 'https://api.whatsapp.com/send',
    ],
    'quiz' => [
        'questions_per_set' => 20,
        'break_after_questions' => 10,
    ],
    'admin' => [
        'report_notification_email' => env('ADMIN_EMAIL', 'info@learnup.com'),
    ],
    'contact' => [
        'address' => "123 Innovation Avenue,\nTech Park, Lahore 54000,\nPakistan",
        'phone_primary' => '+92 300 1234567',
        'phone_secondary' => '+92 321 7654321',
        'email' => 'contact@example.com',
        'whatsapp' => 'https://wa.me/923001234567',
        'youtube' => 'https://youtube.com/@example',
    ],
];
