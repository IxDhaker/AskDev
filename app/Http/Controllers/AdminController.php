<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Question;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized. Admin access required.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_questions' => Question::where('status', '!=', 'closed')->count(),
            'pending_questions' => Question::where('status', 'pending')->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_questions' => Question::with('user')->where('status', '!=', 'closed')->latest()->take(10)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
    public function users()
    {
        $users = User::with('adminProfile')
            ->where('id', '!=', Auth::id())
            ->withCount(['questions', 'reponses', 'votes'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'nullable|in:user,moderator,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['role'])) {
            $updateData['role'] = $validated['role'];
        }

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if (isset($validated['role']) && $validated['role'] === 'admin') {
            if (!$user->adminProfile) {
                Admin::create(['user_id' => $user->id]);
            }
        } else {
            if ($user->adminProfile) {
                $user->adminProfile->delete();
            }
        }

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully!');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully!');
    }

    public function questions()
    {
        $pendingQuestions = Question::with(['user', 'reponses'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $publishedQuestions = Question::with(['user', 'reponses'])
            ->where('status', 'open')
            ->latest()
            ->paginate(15, ['*'], 'published_page');

        $closedQuestions = Question::with(['user', 'reponses'])
            ->where('status', 'closed')
            ->latest()
            ->paginate(15, ['*'], 'closed_page');

        return view('admin.questions.index', compact('pendingQuestions', 'publishedQuestions', 'closedQuestions'));
    }

    public function updateQuestionStatus(Request $request, Question $question)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,closed,pending',
        ]);

        $question->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Question status updated successfully!');
    }
}
