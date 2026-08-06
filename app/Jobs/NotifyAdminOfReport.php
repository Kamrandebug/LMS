<?php

namespace App\Jobs;

use App\Models\QuestionReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyAdminOfReport implements ShouldQueue
{
    use Dispatchable, Queueable;

    public QuestionReport $report;

    public function __construct(QuestionReport $report)
    {
        $this->report = $report;
    }

    public function handle(): void
    {
        $adminEmail = config('learnup.admin.report_notification_email');

        try {
            Mail::raw(
                "New Question Report\n\n" .
                "Type: {$this->report->report_type}\n" .
                "Question ID: {$this->report->question_id}\n" .
                "Description: {$this->report->description}\n" .
                "Reported from IP: {$this->report->reporter_ip}",
                function ($message) use ($adminEmail) {
                    $message->to($adminEmail)
                        ->subject('New Question Report - LearnUp');
                }
            );
        } catch (\Exception $e) {
            Log::warning("Failed to send report notification email: " . $e->getMessage());
        }
    }
}
