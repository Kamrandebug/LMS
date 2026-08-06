<?php

namespace App\Observers;

use App\Jobs\NotifyAdminOfReport;
use App\Models\QuestionReport;

class QuestionReportObserver
{
    public function created(QuestionReport $report): void
    {
        dispatch(new NotifyAdminOfReport($report));
    }
}
