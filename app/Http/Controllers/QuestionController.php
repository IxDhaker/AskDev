<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        // Unique key based on User ID (if logged in) or IP (if guest)
        $identifier = auth()->id() ?: request()->ip();
        $viewKey = 'question_' . $question->id . '_viewed_' . $identifier;

        // Check if the user has already viewed the question
        if (!Cache::has($viewKey)) {
            $question->increment('views');
            // Store in cache to prevent re-counting (valid for 1 year)
            Cache::put($viewKey, true, now()->addYear());
        }

        $question->load(['user', 'reponses.user', 'votes', 'reponses.votes']);

        // Calculate score
        $upVotes = $question->votes()->where('value', 'up')->count();
        $downVotes = $question->votes()->where('value', 'down')->count();
        $score = $upVotes - $downVotes;

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

        return redirect()->route('home')
            ->with('success', 'Question deleted successfully!');
    }
}