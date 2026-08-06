<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubjectRequest;
use App\Models\Subject;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    /**
     * Predefined color gradient options used by the main site cards.
     */
    private array $colorOptions = [
        'from-violet-500 via-purple-500 to-pink-500'   => 'Purple → Pink',
        'from-blue-500 via-cyan-500 to-teal-500'       => 'Blue → Teal',
        'from-green-500 via-emerald-500 to-teal-500'   => 'Green → Teal',
        'from-orange-500 via-red-500 to-pink-500'      => 'Orange → Red',
        'from-yellow-400 via-amber-500 to-orange-500'  => 'Yellow → Orange',
        'from-indigo-500 via-blue-500 to-cyan-500'     => 'Indigo → Cyan',
        'from-red-500 via-rose-500 to-pink-500'        => 'Red → Pink',
        'from-teal-500 via-cyan-500 to-blue-500'       => 'Teal → Blue',
        'from-sky-500 via-blue-500 to-indigo-500'      => 'Sky → Indigo',
        'from-fuchsia-500 via-pink-500 to-rose-500'    => 'Fuchsia → Rose',
    ];

    public function index()
    {
        $subjects = Subject::withCount('topics')->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $colorOptions = $this->colorOptions;
        $nextOrder = Subject::max('sort_order') + 1;

        return view('admin.subjects.create', compact('colorOptions', 'nextOrder'));
    }

    public function store(SubjectRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['slug']);

        Subject::create($data);

        $this->clearSubjectCache();

        return redirect()
            ->route('admin.subjects.index')
            ->with('toast_success', 'Subject "' . $data['name'] . '" created successfully.');
    }

    public function edit(Subject $subject)
    {
        $colorOptions = $this->colorOptions;

        return view('admin.subjects.edit', compact('subject', 'colorOptions'));
    }

    public function update(SubjectRequest $request, Subject $subject)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['slug']);

        $subject->update($data);

        $this->clearSubjectCache();

        return redirect()
            ->route('admin.subjects.index')
            ->with('toast_success', 'Subject "' . $subject->name . '" updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $topicsCount = $subject->topics()->count();

        if ($topicsCount > 0) {
            return redirect()
                ->route('admin.subjects.index')
                ->with('toast_error', 'Cannot delete "' . $subject->name . '" — it has ' . $topicsCount . ' topic(s). Delete all topics first.');
        }

        $name = $subject->name;
        $subject->delete();

        $this->clearSubjectCache();

        return redirect()
            ->route('admin.subjects.index')
            ->with('toast_success', 'Subject "' . $name . '" deleted.');
    }

    private function clearSubjectCache(): void
    {
        // Cache key from ViewServiceProvider.php
        Cache::forget('subjects.nav');
    }
}
