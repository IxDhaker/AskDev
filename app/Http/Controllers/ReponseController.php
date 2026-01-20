<?php

namespace App\Http\Controllers;

use App\Models\Reponse;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReponseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Question $question)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:10',
        ]);

        $reponse = Reponse::create([
            'content' => $validated['content'],
            'question_id' => $question->id,
            'user_id' => Auth::id(),
        ]);

        if ($request->wantsJson()) {
            $reponse->load('user', 'votes'); // Eager load for the view

            $html = view('reponses.partials.card', compact('reponse'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Response added successfully!',
                'html' => $html,
                'count' => $question->reponses()->count()
            ]);
        }

        return redirect()->route('questions.show', $question)
            ->with('success', 'Response added successfully!');
    }

    public function show(Reponse $reponse)
    {
        $reponse->load(['user', 'question']);

        return view('reponses.show', compact('reponse'));
    }


    public function edit(Reponse $reponse)
    {
        if ($reponse->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('reponses.edit', compact('reponse'));
    }
    public function update(Request $request, Reponse $reponse)
    {
        if ($reponse->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:10',
        ]);

        $reponse->update($validated);

        return redirect()->to(route('questions.show', $reponse->question) . '#reponse-' . $reponse->id)
            ->with('success', 'Response updated successfully!');
    }


    public function destroy(Reponse $reponse)
    {
        \Illuminate\Support\Facades\Log::info('Destroy Response Attempt', [
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'reponse_user_id' => $reponse->user_id,
            'reponse_id' => $reponse->id
        ]);

        if ($reponse->user_id != Auth::id()) {
            abort(403, 'Unauthorized action. You are user ' . Auth::id() . ' but this response belongs to user ' . $reponse->user_id);
        }

        $question = $reponse->question;
        $reponse->delete();

        return redirect()->route('questions.show', $question)
            ->with('success', 'Response deleted successfully!');
    }
}
