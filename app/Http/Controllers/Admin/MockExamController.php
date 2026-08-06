<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MockExamRequest;
use App\Models\MockExam;
use Illuminate\Support\Str;

class MockExamController extends Controller
{
    public function index()
    {
        $mockExams = MockExam::withCount('questions')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.mock-tests.index', compact('mockExams'));
    }

    public function create()
    {
        return view('admin.mock-tests.create');
    }

    public function store(MockExamRequest $request)
    {
        $data         = $request->validated();
        $data['slug'] = Str::slug($data['slug']);

        $mockExam = MockExam::create($data);

        return redirect()
            ->route('admin.mock-exams.edit', $mockExam)
            ->with('toast_success', 'Mock Exam "' . $mockExam->name . '" created. Now add questions to it.');
    }

    public function edit(MockExam $mockExam)
    {
        $mockExam->loadCount('questions');

        return view('admin.mock-tests.edit', compact('mockExam'));
    }

    public function update(MockExamRequest $request, MockExam $mockExam)
    {
        $data         = $request->validated();
        $data['slug'] = Str::slug($data['slug']);

        $mockExam->update($data);

        return redirect()
            ->route('admin.mock-exams.edit', $mockExam)
            ->with('toast_success', 'Mock Exam "' . $mockExam->name . '" updated successfully.');
    }

    public function destroy(MockExam $mockExam)
    {
        $name = $mockExam->name;

        // Delete all junction records first, then the exam
        $mockExam->questions()->delete();
        $mockExam->delete();

        return redirect()
            ->route('admin.mock-exams.index')
            ->with('toast_success', 'Mock Exam "' . $name . '" deleted.');
    }
}
