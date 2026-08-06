<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockTest;
use App\Models\MockTestQuestion;
use App\Models\Question;
use App\Models\QuestionSet;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;

class MockTestQuestionController extends Controller
{
    /**
     * Show the question manager for a specific test.
     * Replaces the placeholder card on the mock test edit page.
     */
    public function index(MockTest $mockTest, Request $request)
    {
        // Questions already in this test (snapshot rows)
        $testQuestions = $mockTest->questions()
            ->with('questionSet.topic.subject')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // For the "Add Questions" panel — load filter dropdowns
        $subjects     = Subject::orderBy('name')->get(['id', 'name', 'icon_svg']);
        $topics       = Topic::orderBy('name')->get(['id', 'subject_id', 'name']);
        $questionSets = QuestionSet::orderBy('name')->get(['id', 'topic_id', 'name']);

        // Snapshot identity: a bank question is "already in the test" when the same
        // (question_set_id + question_text) snapshot already exists in the junction.
        $addedKeys = $testQuestions
            ->map(fn ($eq) => (string) $eq->question_set_id . '::' . $eq->question_text)
            ->toArray();

        // Bank: available questions not yet snapshot-copied into this test
        $bankQuery = Question::with('questionSet.topic.subject');

        if ($request->filled('question_set_id')) {
            $bankQuery->where('question_set_id', $request->question_set_id);
        } elseif ($request->filled('topic_id')) {
            $setIds = QuestionSet::where('topic_id', $request->topic_id)->pluck('id');
            $bankQuery->whereIn('question_set_id', $setIds);
        } elseif ($request->filled('subject_id')) {
            $topicIds = Topic::where('subject_id', $request->subject_id)->pluck('id');
            $setIds   = QuestionSet::whereIn('topic_id', $topicIds)->pluck('id');
            $bankQuery->whereIn('question_set_id', $setIds);
        }

        $bankQuestions = $bankQuery
            ->orderBy('question_set_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->reject(function ($q) use ($addedKeys) {
                return in_array((string) $q->question_set_id . '::' . $q->question, $addedKeys, true);
            })
            ->values();

        $selectedSubjectId = $request->input('subject_id');
        $selectedTopicId   = $request->input('topic_id');
        $selectedSetId     = $request->input('question_set_id');

        return view('admin.mock-test-questions.index', compact(
            'mockTest',
            'testQuestions',
            'bankQuestions',
            'subjects',
            'topics',
            'questionSets',
            'selectedSubjectId',
            'selectedTopicId',
            'selectedSetId'
        ));
    }

    /**
     * Add a single question to the test (copies a snapshot of the bank question).
     */
    public function store(Request $request, MockTest $mockTest)
    {
        $request->validate([
            'question_id' => ['required', 'integer', 'exists:questions,id'],
        ]);

        $question = Question::findOrFail($request->question_id);

        $alreadyAdded = MockTestQuestion::where('mock_exam_id', $mockTest->id)
            ->where('question_set_id', $question->question_set_id)
            ->where('question_text', $question->question)
            ->exists();

        if ($alreadyAdded) {
            return redirect()
                ->route('admin.mock-test-questions.index', $mockTest)
                ->with('toast_error', 'That question is already in this test.');
        }

        // Assign next sort_order
        $nextOrder = MockTestQuestion::where('mock_exam_id', $mockTest->id)->max('sort_order');
        $nextOrder = ($nextOrder ?? 0) + 1;

        MockTestQuestion::create([
            'mock_exam_id'    => $mockTest->id,
            'question_set_id' => $question->question_set_id,
            'question_text'   => $question->question,
            'option_a'        => $question->option_a,
            'option_b'        => $question->option_b,
            'option_c'        => $question->option_c,
            'option_d'        => $question->option_d,
            'correct_option'  => $question->correct_option,
            'explanation'     => $question->explanation,
            'sort_order'      => $nextOrder,
        ]);

        return redirect()
            ->route('admin.mock-test-questions.index', $mockTest)
            ->with('toast_success', 'Question added to test.');
    }

    /**
     * Add multiple questions at once (bulk add from bank).
     */
    public function bulkStore(Request $request, MockTest $mockTest)
    {
        $request->validate([
            'question_ids'   => ['required', 'array', 'min:1'],
            'question_ids.*' => ['integer', 'exists:questions,id'],
        ]);

        $questions = Question::whereIn('id', $request->question_ids)->get();

        $existingKeys = MockTestQuestion::where('mock_exam_id', $mockTest->id)
            ->get(['question_set_id', 'question_text'])
            ->map(fn ($eq) => (string) $eq->question_set_id . '::' . $eq->question_text)
            ->toArray();

        $nextOrder = MockTestQuestion::where('mock_exam_id', $mockTest->id)->max('sort_order') ?? 0;

        $added = 0;
        foreach ($questions as $question) {
            $key = (string) $question->question_set_id . '::' . $question->question;

            if (in_array($key, $existingKeys, true)) {
                continue;
            }

            $nextOrder++;
            MockTestQuestion::create([
                'mock_exam_id'    => $mockTest->id,
                'question_set_id' => $question->question_set_id,
                'question_text'   => $question->question,
                'option_a'        => $question->option_a,
                'option_b'        => $question->option_b,
                'option_c'        => $question->option_c,
                'option_d'        => $question->option_d,
                'correct_option'  => $question->correct_option,
                'explanation'     => $question->explanation,
                'sort_order'      => $nextOrder,
            ]);
            $added++;
        }

        $skipped = count($request->question_ids) - $added;
        $message = $added . ' question(s) added.';
        if ($skipped > 0) {
            $message .= ' ' . $skipped . ' already in test (skipped).';
        }

        return redirect()
            ->route('admin.mock-test-questions.index', $mockTest)
            ->with('toast_success', $message);
    }

    /**
     * Remove a question from the test.
     * Destroys the MockTestQuestion junction record only — the Question itself is never deleted.
     */
    public function destroy(MockTest $mockTest, MockTestQuestion $mockTestQuestion)
    {
        // Safety: ensure this junction record belongs to this test
        if ($mockTestQuestion->mock_exam_id !== $mockTest->id) {
            abort(403, 'This question does not belong to this test.');
        }

        $mockTestQuestion->delete();

        return redirect()
            ->route('admin.mock-test-questions.index', $mockTest)
            ->with('toast_success', 'Question removed from test.');
    }

    /**
     * Update sort_order of a single test question.
     */
    public function updateOrder(Request $request, MockTest $mockTest, MockTestQuestion $mockTestQuestion)
    {
        $request->validate([
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        if ($mockTestQuestion->mock_exam_id !== $mockTest->id) {
            abort(403);
        }

        $mockTestQuestion->update(['sort_order' => $request->sort_order]);

        return redirect()
            ->route('admin.mock-test-questions.index', $mockTest)
            ->with('toast_success', 'Order updated.');
    }
}
