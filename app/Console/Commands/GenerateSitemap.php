<?php

namespace App\Console\Commands;

use App\Models\QuestionSet;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml for search engines';

    public function handle(): int
    {
        $baseUrl = config('app.url');
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

        // Static pages
        $staticUrls = [
            '/' => '1.0',
            '/privacy-policy' => '0.3',
            '/terms-of-service' => '0.3',
            '/mock-tests' => '0.9',
        ];

        foreach ($staticUrls as $path => $priority) {
            $this->addUrl($xml, $baseUrl . $path, $priority, 'daily');
        }

        // Subjects
        $subjects = Subject::active()->get();
        foreach ($subjects as $subject) {
            $this->addUrl($xml, $baseUrl . "/subjects/{$subject->slug}", '0.9', 'weekly');
        }

        // Topics
        $topics = Topic::active()->get();
        foreach ($topics as $topic) {
            if ($topic->subject) {
                $this->addUrl($xml, $baseUrl . "/subjects/{$topic->subject->slug}/{$topic->slug}", '0.8', 'weekly');
            }
        }

        // Sets
        $sets = QuestionSet::active()->get();
        foreach ($sets as $set) {
            if ($set->topic && $set->topic->subject) {
                $this->addUrl(
                    $xml,
                    $baseUrl . "/subjects/{$set->topic->subject->slug}/{$set->topic->slug}/set-{$set->set_number}",
                    '0.7',
                    'monthly'
                );
            }
        }

        // Save
        $xml->saveXML(public_path('sitemap.xml'));
        $this->info('Sitemap generated: ' . public_path('sitemap.xml'));

        return Command::SUCCESS;
    }

    private function addUrl(\SimpleXMLElement $xml, string $loc, string $priority = '0.5', string $changefreq = 'monthly'): void
    {
        $url = $xml->addChild('url');
        $url->addChild('loc', htmlspecialchars($loc));
        $url->addChild('lastmod', now()->toDateString());
        $url->addChild('changefreq', $changefreq);
        $url->addChild('priority', $priority);
    }
}
