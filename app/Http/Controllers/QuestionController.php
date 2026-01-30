<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminActivityNotification;
use App\Notifications\UserNotification;

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

        $status = (Auth::user()->role === 'admin') ? 'open' : 'pending';

        $question = Question::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'] ?? $status,
            'user_id' => Auth::id(),
        ]);

        // Notify Admins
        // Notify Admins
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();

        $adminMsg = ($question->status === 'pending')
            ? 'New pending question: ' . $question->title
            : 'New question posted: ' . $question->title;

        Notification::send($admins, new AdminActivityNotification([
            'type' => 'question_created',
            'message' => $adminMsg,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'link' => route('questions.show', $question)
        ]));

        $message = ($question->status === 'open')
            ? 'Question created successfully!'
            : 'Question created successfully! It is pending approval.';

        // Notify User if pending
        if ($question->status === 'pending') {
            $question->user->notify(new UserNotification([
                'type' => 'question_pending',
                'message' => 'Your question "' . $question->title . '" is pending approval.',
                'link' => route('questions.show', $question)
            ]));
        }

        return redirect()->route('questions.show', $question)
            ->with('success', $message);
    }
    public function show(Question $question)
    {
        // Check visibility
        if ($question->status !== 'open') {
            $user = Auth::user();
            if (!$user || ($user->id !== $question->user_id && $user->role !== 'admin')) {
                abort(404);
            }
        }

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
        if ($question->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Notify User if deleted by Admin (Rejection)
        if (Auth::user()->role === 'admin' && $question->user_id !== Auth::id()) {
            $question->user->notify(new UserNotification([
                'type' => 'question_rejected',
                'message' => 'Your question "' . $question->title . '" has been rejected/deleted by an admin.',
                'link' => null // No link because it's deleted
            ]));
        }

        $question->delete();

        // Notify Admins
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
        Notification::send($admins, new AdminActivityNotification([
            'type' => 'question_deleted',
            'message' => 'Question deleted: ' . $question->title,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'link' => null
        ]));

        return redirect()->route('home')
            ->with('success', 'Question deleted successfully!');
    }
}