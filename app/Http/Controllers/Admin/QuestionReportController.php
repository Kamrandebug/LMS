<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionReport;
use App\Models\QuestionSet;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;

class QuestionReportController extends Controller
{
    /**
     * List all question reports with filters.
     */
    public function index(Request $request)
    {
        $subjects     = Subject::orderBy('name')->get(['id', 'name', 'icon_svg']);
        $topics       = Topic::orderBy('name')->get(['id', 'subject_id', 'name']);
        $questionSets = QuestionSet::orderBy('name')->get(['id', 'topic_id', 'name']);

        $query = QuestionReport::with('question.questionSet.topic.subject');

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Question Set filter
        if ($request->filled('question_set_id')) {
            $query->whereHas('question', function ($q) use ($request) {
                $q->where('question_set_id', $request->question_set_id);
            });
        } elseif ($request->filled('topic_id')) {
            $setIds = QuestionSet::where('topic_id', $request->topic_id)->pluck('id');
            $query->whereHas('question', function ($q) use ($setIds) {
                $q->whereIn('question_set_id', $setIds);
            });
        } elseif ($request->filled('subject_id')) {
            $topicIds = Topic::where('subject_id', $request->subject_id)->pluck('id');
            $setIds   = QuestionSet::whereIn('topic_id', $topicIds)->pluck('id');
            $query->whereHas('question', function ($q) use ($setIds) {
                $q->whereIn('question_set_id', $setIds);
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(25);

        // Stats for header badges
        $pendingCount  = QuestionReport::where('status', 'pending')->count();
        $reviewedCount = QuestionReport::where('status', 'reviewed')->count();
        $resolvedCount = QuestionReport::where('status', 'resolved')->count();

        $selectedStatus   = $request->input('status');
        $selectedSubjectId = $request->input('subject_id');
        $selectedTopicId   = $request->input('topic_id');
        $selectedSetId     = $request->input('question_set_id');

        return view('admin.reports.index', compact(
            'reports',
            'subjects',
            'topics',
            'questionSets',
            'pendingCount',
            'reviewedCount',
            'resolvedCount',
            'selectedStatus',
            'selectedSubjectId',
            'selectedTopicId',
            'selectedSetId'
        ));
    }

    /**
     * Show a single report in detail.
     */
    public function show(QuestionReport $report)
    {
        $report->load('question.questionSet.topic.subject');

        return view('admin.reports.show', compact('report'));
    }

    /**
     * Mark a report as resolved.
     */
    public function resolve(Request $request, QuestionReport $report)
    {
        if ($report->status === 'resolved') {
            return redirect()
                ->route('admin.reports.index')
                ->with('toast_error', 'This report is already resolved.');
        }

        $report->update([
            'status'      => 'resolved',
            'admin_notes' => $this->appendAdminNote($report, $request->input('admin_notes')),
        ]);

        return redirect()
            ->route('admin.reports.index')
            ->with('toast_success', 'Report #' . $report->id . ' marked as resolved.');
    }

    /**
     * Mark a report as reviewed (dismissed — not a valid issue).
     */
    public function dismiss(Request $request, QuestionReport $report)
    {
        if ($report->status === 'reviewed') {
            return redirect()
                ->route('admin.reports.index')
                ->with('toast_error', 'This report is already reviewed.');
        }

        $report->update([
            'status'      => 'reviewed',
            'admin_notes' => $this->appendAdminNote($report, $request->input('admin_notes')),
        ]);

        return redirect()
            ->route('admin.reports.index')
            ->with('toast_success', 'Report #' . $report->id . ' reviewed and closed.');
    }

    /**
     * Reopen a resolved/reviewed report back to pending.
     */
    public function reopen(Request $request, QuestionReport $report)
    {
        if ($report->status === 'pending') {
            return redirect()
                ->route('admin.reports.index')
                ->with('toast_error', 'This report is already pending.');
        }

        $report->update([
            'status' => 'pending',
        ]);

        return redirect()
            ->route('admin.reports.index')
            ->with('toast_success', 'Report #' . $report->id . ' reopened.');
    }

    /**
     * Bulk resolve multiple pending reports.
     */
    public function bulkResolve(Request $request)
    {
        $request->validate([
            'report_ids'   => ['required', 'array', 'min:1'],
            'report_ids.*' => ['integer', 'exists:question_reports,id'],
        ]);

        $count = QuestionReport::whereIn('id', $request->report_ids)
            ->where('status', 'pending')
            ->update(['status' => 'resolved']);

        $skipped = count($request->report_ids) - $count;
        $message = $count . ' report(s) resolved.';
        if ($skipped > 0) {
            $message .= ' ' . $skipped . ' already handled (skipped).';
        }

        return redirect()
            ->route('admin.reports.index')
            ->with('toast_success', $message);
    }

    /**
     * Bulk dismiss (review) multiple pending reports.
     */
    public function bulkDismiss(Request $request)
    {
        $request->validate([
            'report_ids'   => ['required', 'array', 'min:1'],
            'report_ids.*' => ['integer', 'exists:question_reports,id'],
        ]);

        $count = QuestionReport::whereIn('id', $request->report_ids)
            ->where('status', 'pending')
            ->update(['status' => 'reviewed']);

        $skipped = count($request->report_ids) - $count;
        $message = $count . ' report(s) reviewed.';
        if ($skipped > 0) {
            $message .= ' ' . $skipped . ' already handled (skipped).';
        }

        return redirect()
            ->route('admin.reports.index')
            ->with('toast_success', $message);
    }

    /**
     * Append an optional admin note to the existing notes (audit trail).
     */
    private function appendAdminNote(QuestionReport $report, ?string $note): ?string
    {
        $note = trim($note ?? '');

        if ($note === '') {
            return $report->admin_notes;
        }

        return trim($report->admin_notes ?? '') !== ''
            ? trim($report->admin_notes) . "\n" . $note
            : $note;
    }
}
