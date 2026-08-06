<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TopicRequest;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::orderBy('name')->get(['id', 'name', 'icon_svg']);

        $query = Topic::with('subject')
            ->withCount('questionSets');

        // Filter by subject if requested
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $topics = $query->orderBy('subject_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $selectedSubjectId = $request->subject_id;

        return view('admin.topics.index', compact('topics', 'subjects', 'selectedSubjectId'));
    }

    public function create(Request $request)
    {
        $subjects        = Subject::where('is_active', true)->orderBy('name')->get(['id', 'name', 'icon_svg']);
        $selectedSubject = $request->filled('subject_id') ? $request->subject_id : null;

        return view('admin.topics.create', compact('subjects', 'selectedSubject'));
    }

    public function store(TopicRequest $request)
    {
        $data         = $request->validated();
        $data['slug'] = Str::slug($data['slug']);

        Topic::create($data);

        $this->clearCache();

        return redirect()
            ->route('admin.topics.index', ['subject_id' => $data['subject_id']])
            ->with('toast_success', 'Topic "' . $data['name'] . '" created successfully.');
    }

    public function edit(Topic $topic)
    {
        $subjects = Subject::orderBy('name')->get(['id', 'name', 'icon_svg']);

        return view('admin.topics.edit', compact('topic', 'subjects'));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $data         = $request->validated();
        $data['slug'] = Str::slug($data['slug']);

        $topic->update($data);

        $this->clearCache();

        return redirect()
            ->route('admin.topics.index', ['subject_id' => $topic->subject_id])
            ->with('toast_success', 'Topic "' . $topic->name . '" updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        $setsCount = $topic->questionSets()->count();

        if ($setsCount > 0) {
            return redirect()
                ->route('admin.topics.index')
                ->with('toast_error', 'Cannot delete "' . $topic->name . '" — it has ' . $setsCount . ' question set(s). Delete all sets first.');
        }

        $name      = $topic->name;
        $subjectId = $topic->subject_id;
        $topic->delete();

        $this->clearCache();

        return redirect()
            ->route('admin.topics.index', ['subject_id' => $subjectId])
            ->with('toast_success', 'Topic "' . $name . '" deleted.');
    }

    private function clearCache(): void
    {
        // Clear the same cache key used in ViewServiceProvider for navSubjects
        Cache::forget('subjects.nav');
    }
}
