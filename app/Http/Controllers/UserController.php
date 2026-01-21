<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }
    public function show(User $user)
    {
        $user->load([
            'questions' => function ($query) {
                $query->latest()->take(10);
            },
            'reponses' => function ($query) {
                $query->with('question')->latest()->take(10);
            },
        ]);

        $stats = [
            'total_questions' => $user->questions()->count(),
            'total_reponses' => $user->reponses()->count(),
            'total_votes' => $user->votes()->count(),
        ];

        return view('users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        // Check if user is authorized to edit
        if ($user->id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('users.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        if ($user->id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Verify current password if changing password
        if (!empty($validated['password'])) {
            if (
                empty($validated['current_password']) ||
                !Hash::check($validated['current_password'], $user->password)
            ) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Current password is incorrect.'])
                    ->withInput();
            }
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.show', $user)
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Display the user's activity feed.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\View
     */
    public function activity(User $user)
    {
        $questions = $user->questions()
            ->with('reponses')
            ->latest()
            ->paginate(10, ['*'], 'questions_page');

        $reponses = $user->reponses()
            ->with('question')
            ->latest()
            ->paginate(10, ['*'], 'reponses_page');

        return view('users.activity', compact('user', 'questions', 'reponses'));
    }
    public function archiveQuestions(User $user)
    {
        $questions = $user->questions()
            ->where('status', '!=', 'closed')
            ->latest()
            ->paginate(15);
        return view('users.archive.questions', compact('user', 'questions'));
    }

    public function archiveReponses(User $user)
    {
        $reponses = $user->reponses()->with('question')->latest()->paginate(15);
        return view('users.archive.reponses', compact('user', 'reponses'));
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password provided.']);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Your account has been deleted successfully.');
    }

    public function checkPassword(Request $request)
    {
        $request->validate(['password' => 'required']);
        $user = Auth::user();

        if (Hash::check($request->password, $user->password)) {
            return response()->json(['valid' => true]);
        }

        return response()->json(['valid' => false]);
    }
}
