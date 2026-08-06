<?php

namespace Database\Seeders;

use App\Models\DailyQuote;
use Illuminate\Database\Seeder;

class DailyQuoteSeeder extends Seeder
{
    public function run(): void
    {
        $quotes = [
            // Islamic Quotes (show on Fridays)
            ['quote' => 'The best of you are those who are best to others.', 'type' => 'islamic', 'author' => 'Prophet Muhammad (PBUH)', 'is_friday_special' => true],
            ['quote' => 'Seeking knowledge is an obligation upon every Muslim.', 'type' => 'islamic', 'author' => 'Prophet Muhammad (PBUH)', 'is_friday_special' => true],
            ['quote' => 'Whoever treads a path in search of knowledge, Allah will make easy for him the path to Paradise.', 'type' => 'islamic', 'author' => 'Prophet Muhammad (PBUH)', 'is_friday_special' => true],
            ['quote' => 'The ink of the scholar is more holy than the blood of the martyr.', 'type' => 'islamic', 'author' => 'Prophet Muhammad (PBUH)', 'is_friday_special' => true],
            ['quote' => 'Allah does not burden a soul beyond that it can bear.', 'type' => 'islamic', 'author' => 'Quran (2:286)', 'is_friday_special' => true],
            ['quote' => 'So verily, with the hardship, there is relief.', 'type' => 'islamic', 'author' => 'Quran (94:5)', 'is_friday_special' => true],

            // Motivation Quotes
            ['quote' => 'Start where you are. Use what you have. Do what you can.', 'type' => 'motivation', 'author' => 'Arthur Ashe'],
            ['quote' => 'The secret of getting ahead is getting started.', 'type' => 'motivation', 'author' => 'Mark Twain'],
            ['quote' => 'Success is not final, failure is not fatal: it is the courage to continue that counts.', 'type' => 'motivation', 'author' => 'Winston Churchill'],
            ['quote' => 'Education is the most powerful weapon which you can use to change the world.', 'type' => 'motivation', 'author' => 'Nelson Mandela'],
            ['quote' => 'The expert in anything was once a beginner.', 'type' => 'motivation', 'author' => 'Helen Hayes'],
            ['quote' => 'It does not matter how slowly you go as long as you do not stop.', 'type' => 'motivation', 'author' => 'Confucius'],
            ['quote' => 'Believe you can and you\'re halfway there.', 'type' => 'motivation', 'author' => 'Theodore Roosevelt'],
            ['quote' => 'Your ability to learn faster than your competition is your only sustainable competitive advantage.', 'type' => 'motivation', 'author' => 'Arie de Geus'],

            // Facts
            ['quote' => 'The human brain can store approximately 2.5 petabytes of data — equivalent to 3 million hours of TV.', 'type' => 'fact'],
            ['quote' => 'Pakistan\'s Karakoram Highway is the highest paved international road in the world.', 'type' => 'fact'],
            ['quote' => 'Malala Yousafzai is the youngest-ever Nobel Peace Prize laureate, winning at age 17.', 'type' => 'fact'],
            ['quote' => 'The first computer virus was created in 1983 and was called the "Elk Cloner."', 'type' => 'fact'],
            ['quote' => 'The Internet weighs about 50 grams — the weight of a strawberry — as the weight of electrons moving through it.', 'type' => 'fact'],
            ['quote' => 'Pakistan has the largest canal system in the world.', 'type' => 'fact'],
            ['quote' => 'Over 80% of the world\'s data was created in the last two years.', 'type' => 'fact'],
        ];

        foreach ($quotes as $data) {
            DailyQuote::create(array_merge($data, ['is_active' => true]));
        }
    }
}
