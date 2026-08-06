<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MockTestRequest;
use App\Models\MockTest;
use Illuminate\Support\Str;

class MockTestController extends Controller
{
    public function index()
    {
        $mockTests = MockTest::withCount('questions')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.mock-tests.index', compact('mockTests'));
    }

    public function create()
    {
        return view('admin.mock-tests.create');
    }

    public function store(MockTestRequest $request)
    {
        $data         = $request->validated();
        $data['slug'] = Str::slug($data['slug']);

        $mockTest = MockTest::create($data);

        return redirect()
            ->route('admin.mock-tests.edit', $mockTest)
            ->with('toast_success', 'Mock Test "' . $mockTest->name . '" created. Now add questions to it.');
    }

    public function edit(MockTest $mockTest)
    {
        $mockTest->loadCount('questions');

        return view('admin.mock-tests.edit', compact('mockTest'));
    }

    public function update(MockTestRequest $request, MockTest $mockTest)
    {
        $data         = $request->validated();
        $data['slug'] = Str::slug($data['slug']);

        $mockTest->update($data);

        return redirect()
            ->route('admin.mock-tests.edit', $mockTest)
            ->with('toast_success', 'Mock Test "' . $mockTest->name . '" updated successfully.');
    }

    public function destroy(MockTest $mockTest)
    {
        $name = $mockTest->name;

        // Delete all junction records first, then the test
        $mockTest->questions()->delete();
        $mockTest->delete();

        return redirect()
            ->route('admin.mock-tests.index')
            ->with('toast_success', 'Mock Test "' . $name . '" deleted.');
    }
}
