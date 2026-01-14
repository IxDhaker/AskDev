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
        if ($reponse->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('reponses.edit', compact('reponse'));
    }
    public function update(Request $request, Reponse $reponse)
    {
        if ($reponse->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:10',
        ]);

        $reponse->update($validated);

        return redirect()->route('questions.show', $reponse->question)
            ->with('success', 'Response updated successfully!');
    }

    
    public function destroy(Reponse $reponse)
    {
        if ($reponse->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $question = $reponse->question;
        $reponse->delete();

        return redirect()->route('questions.show', $question)
            ->with('success', 'Response deleted successfully!');
    }
}
