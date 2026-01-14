<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    public function index()
    {
        $questions = Question::with(['user', 'reponses'])
            ->latest()
            ->paginate(15);

        return view('questions.index', compact('questions'));
    }
    public function create()
    {
        return view('questions.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'status' => 'nullable|in:open,closed,pending',
        ]);

        $question = Question::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'] ?? 'open',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('questions.show', $question)
            ->with('success', 'Question created successfully!');
    }
    public function show(Question $question)
    {
        $key = 'question_' . $question->id . '_viewed_by_' . (auth()->id() ?: request()->ip());
        if (!\Illuminate\Support\Facades\Cache::has($key)) {
            $question->increment('views');
            \Illuminate\Support\Facades\Cache::put($key, true, now()->addYear());
        }

        $question->load(['user', 'reponses.user', 'votes']);
        $score = $question->votes()->sum('value') === 0 ? 0 :
            $question->votes()->where('value', 'up')->count() - $question->votes()->where('value', 'down')->count();

        return view('questions.show', compact('question', 'score'));
    }
    public function edit(Question $question)
    {
        if ($question->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('questions.edit', compact('question'));
    }
    public function update(Request $request, Question $question)
    {
        if ($question->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'status' => 'nullable|in:open,closed,pending',
        ]);

        $question->update($validated);

        return redirect()->route('questions.show', $question)
            ->with('success', 'Question updated successfully!');
    }
    public function destroy(Question $question)
    {
        if ($question->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $question->delete();

        return redirect()->route('questions.index')
            ->with('success', 'Question deleted successfully!');
    }
}