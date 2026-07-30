<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::withCount('questions')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.tests.index', compact('tests'));
    }

    public function create()
    {
        return view('admin.tests.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'required|integer|min:1|max:180',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $test = Test::create([
            'title' => $request->title,
            'description' => $request->description,
            'time_limit' => $request->time_limit,
            'passing_score' => $request->passing_score,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.tests.constructor', $test)
            ->with('success', 'Тест создан! Теперь добавьте вопросы.');
    }

    public function edit(Test $test)
    {
        return view('admin.tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'required|integer|min:1|max:180',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $test->update([
            'title' => $request->title,
            'description' => $request->description,
            'time_limit' => $request->time_limit,
            'passing_score' => $request->passing_score,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.tests.index')
            ->with('success', 'Тест обновлен!');
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('admin.tests.index')
            ->with('success', 'Тест удален!');
    }

    public function constructor(Test $test)
    {
        $questions = $test->questions()
            ->with('answers')
            ->orderBy('order')
            ->get();

        return view('admin.tests.constructor', compact('test', 'questions'));
    }
}
