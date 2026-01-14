<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function vote(Request $request, Question $question)
    {
        $validated = $request->validate([
            'value' => 'required|in:up,down',
        ]);

        $existingVote = Vote::where('user_id', Auth::id())
            ->where('question_id', $question->id)
            ->first();

        if ($existingVote) {
            if ($existingVote->value === $validated['value']) {
                $existingVote->delete();
                $message = 'Vote removed successfully!';
            } else {
                $existingVote->update(['value' => $validated['value']]);
                $message = 'Vote updated successfully!';
            }
        } else {
            Vote::create([
                'value' => $validated['value'],
                'user_id' => Auth::id(),
                'question_id' => $question->id,
            ]);
            $message = 'Vote cast successfully!';
        }

        return redirect()->back()->with('success', $message);
    }
    public function removeVote(Question $question)
    {
        $vote = Vote::where('user_id', Auth::id())
            ->where('question_id', $question->id)
            ->first();

        if ($vote) {
            $vote->delete();
            return redirect()->back()->with('success', 'Vote removed successfully!');
        }

        return redirect()->back()->with('error', 'No vote found to remove.');
    }
}
