<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuestionSetRequest;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\QuestionSet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuestionSetController extends Controller
{
    public function index(Request $request)
    {
        $subjects          = Subject::orderBy('name')->get(['id', 'name', 'icon_svg']);
        $topics            = Topic::orderBy('name')->get(['id', 'subject_id', 'name']);
        $selectedSubjectId = $request->input('subject_id');
        $selectedTopicId   = $request->input('topic_id');

        $query = QuestionSet::with(['topic.subject'])
            ->withCount('questions');

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        } elseif ($request->filled('subject_id')) {
            $topicIds = Topic::where('subject_id', $request->subject_id)->pluck('id');
            $query->whereIn('topic_id', $topicIds);
        }

        $questionSets = $query
            ->orderBy('topic_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.question-sets.index', compact(
            'questionSets', 'subjects', 'topics',
            'selectedSubjectId', 'selectedTopicId'
        ));
    }

    public function create(Request $request)
    {
        $subjects        = Subject::where('is_active', true)->orderBy('name')->get(['id', 'name', 'icon_svg']);
        $topics          = Topic::where('is_active', true)->orderBy('name')->get(['id', 'subject_id', 'name']);
        $selectedSubject = $request->input('subject_id');
        $selectedTopic   = $request->input('topic_id');

        return view('admin.question-sets.create', compact(
            'subjects', 'topics', 'selectedSubject', 'selectedTopic'
        ));
    }

    public function store(QuestionSetRequest $request)
    {
        $data         = $request->validated();
        $data['slug'] = Str::slug($data['slug']);

        $set = QuestionSet::create($data);

        return redirect()
            ->route('admin.question-sets.index', [
                'topic_id'   => $set->topic_id,
                'subject_id' => $set->topic->subject_id,
            ])
            ->with('toast_success', 'Question Set "' . $set->name . '" created successfully.');
    }

    public function edit(QuestionSet $questionSet)
    {
        $subjects = Subject::orderBy('name')->get(['id', 'name', 'icon_svg']);
        $topics   = Topic::orderBy('name')->get(['id', 'subject_id', 'name']);

        return view('admin.question-sets.edit', compact('questionSet', 'subjects', 'topics'));
    }

    public function update(QuestionSetRequest $request, QuestionSet $questionSet)
    {
        $data         = $request->validated();
        $data['slug'] = Str::slug($data['slug']);

        $questionSet->update($data);

        return redirect()
            ->route('admin.question-sets.index', [
                'topic_id'   => $questionSet->topic_id,
                'subject_id' => $questionSet->topic->subject_id,
            ])
            ->with('toast_success', 'Question Set "' . $questionSet->name . '" updated successfully.');
    }

    public function destroy(QuestionSet $questionSet)
    {
        $questionsCount = $questionSet->questions()->count();

        if ($questionsCount > 0) {
            return redirect()
                ->route('admin.question-sets.index')
                ->with('toast_error', 'Cannot delete "' . $questionSet->name . '" — it has ' . $questionsCount . ' question(s). Delete all questions first.');
        }

        $name      = $questionSet->name;
        $topicId   = $questionSet->topic_id;
        $subjectId = $questionSet->topic->subject_id;
        $questionSet->delete();

        return redirect()
            ->route('admin.question-sets.index', [
                'topic_id'   => $topicId,
                'subject_id' => $subjectId,
            ])
            ->with('toast_success', 'Question Set "' . $name . '" deleted.');
    }
}
