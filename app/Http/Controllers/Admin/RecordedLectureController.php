<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RecordedLectureRequest;
use App\Models\RecordedLecture;
use App\Models\Subject;

class RecordedLectureController extends Controller
{
    private function subjectsWithTopics()
    {
        return Subject::with(['topics' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function index()
    {
        $lectures = RecordedLecture::with('topic.subject')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.recorded-lectures.index', compact('lectures'));
    }

    public function create()
    {
        $subjects = $this->subjectsWithTopics();
        return view('admin.recorded-lectures.create', compact('subjects'));
    }

    public function store(RecordedLectureRequest $request)
    {
        RecordedLecture::create($request->validated());
        return redirect()->route('admin.recorded-lectures.index')
            ->with('toast_success', 'Recorded lecture created successfully.');
    }

    public function edit(RecordedLecture $recordedLecture)
    {
        $subjects = $this->subjectsWithTopics();
        return view('admin.recorded-lectures.edit', compact('recordedLecture', 'subjects'));
    }

    public function update(RecordedLectureRequest $request, RecordedLecture $recordedLecture)
    {
        $recordedLecture->update($request->validated());
        return redirect()->route('admin.recorded-lectures.index')
            ->with('toast_success', 'Recorded lecture updated successfully.');
    }

    public function destroy(RecordedLecture $recordedLecture)
    {
        $recordedLecture->delete();
        return redirect()->route('admin.recorded-lectures.index')
            ->with('toast_success', 'Recorded lecture deleted successfully.');
    }
}
