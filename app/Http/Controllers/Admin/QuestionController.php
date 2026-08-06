<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuestionRequest;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\QuestionSet;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $subjects            = Subject::orderBy('name')->get(['id', 'name', 'icon_svg']);
        $topics              = Topic::orderBy('name')->get(['id', 'subject_id', 'name']);
        $questionSets        = QuestionSet::orderBy('name')->get(['id', 'topic_id', 'name']);
        $selectedSubjectId   = $request->input('subject_id');
        $selectedTopicId     = $request->input('topic_id');
        $selectedSetId       = $request->input('question_set_id');

        $query = Question::with(['questionSet.topic.subject']);

        if ($request->filled('question_set_id')) {
            $query->where('question_set_id', $request->question_set_id);
        } elseif ($request->filled('topic_id')) {
            $setIds = QuestionSet::where('topic_id', $request->topic_id)->pluck('id');
            $query->whereIn('question_set_id', $setIds);
        } elseif ($request->filled('subject_id')) {
            $topicIds = Topic::where('subject_id', $request->subject_id)->pluck('id');
            $setIds   = QuestionSet::whereIn('topic_id', $topicIds)->pluck('id');
            $query->whereIn('question_set_id', $setIds);
        }

        $questions = $query
            ->orderBy('question_set_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.questions.index', compact(
            'questions', 'subjects', 'topics', 'questionSets',
            'selectedSubjectId', 'selectedTopicId', 'selectedSetId'
        ));
    }

    public function create(Request $request)
    {
        $subjects     = Subject::where('is_active', true)->orderBy('name')->get(['id', 'name', 'icon_svg']);
        $topics       = Topic::where('is_active', true)->orderBy('name')->get(['id', 'subject_id', 'name']);
        $questionSets = QuestionSet::where('is_active', true)->orderBy('name')->get(['id', 'topic_id', 'name']);

        $selectedSubject = $request->input('subject_id');
        $selectedTopic   = $request->input('topic_id');
        $selectedSet     = $request->input('question_set_id');

        return view('admin.questions.create', compact(
            'subjects', 'topics', 'questionSets',
            'selectedSubject', 'selectedTopic', 'selectedSet'
        ));
    }

    public function store(QuestionRequest $request)
    {
        $question = Question::create($request->validated());

        return redirect()
            ->route('admin.questions.index', ['question_set_id' => $question->question_set_id])
            ->with('toast_success', 'Question created successfully.');
    }

    public function edit(Question $question)
    {
        $subjects     = Subject::orderBy('name')->get(['id', 'name', 'icon_svg']);
        $topics       = Topic::orderBy('name')->get(['id', 'subject_id', 'name']);
        $questionSets = QuestionSet::orderBy('name')->get(['id', 'topic_id', 'name']);

        return view('admin.questions.edit', compact('question', 'subjects', 'topics', 'questionSets'));
    }

    public function update(QuestionRequest $request, Question $question)
    {
        $question->update($request->validated());

        return redirect()
            ->route('admin.questions.index', ['question_set_id' => $question->question_set_id])
            ->with('toast_success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $setId = $question->question_set_id;
        $question->delete();

        return redirect()
            ->route('admin.questions.index', ['question_set_id' => $setId])
            ->with('toast_success', 'Question deleted.');
    }
}
