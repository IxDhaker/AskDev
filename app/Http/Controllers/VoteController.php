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

        $user = Auth::user();
        $existingVote = Vote::where('user_id', $user->id)
            ->where('question_id', $question->id)
            ->first();

        $voteStatus = null;

        if ($existingVote) {
            if ($existingVote->value === $validated['value']) {
                $existingVote->delete();
                $message = 'Vote removed successfully!';
            } else {
                $existingVote->update(['value' => $validated['value']]);
                $message = 'Vote updated successfully!';
                $voteStatus = $validated['value'];
            }
        } else {
            Vote::create([
                'value' => $validated['value'],
                'user_id' => $user->id,
                'question_id' => $question->id,
            ]);
            $message = 'Vote cast successfully!';
            $voteStatus = $validated['value'];
        }

        if ($request->wantsJson() || $request->ajax()) {
            // Calculate new score
            $upVotes = $question->votes()->where('value', 'up')->count();
            $downVotes = $question->votes()->where('value', 'down')->count();
            $score = $upVotes - $downVotes;

            return response()->json([
                'success' => true,
                'message' => $message,
                'score' => $score,
                'user_vote' => $voteStatus
            ]);
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

    public function voteReponse(Request $request, \App\Models\Reponse $reponse)
    {
        $validated = $request->validate([
            'value' => 'required|in:up,down',
        ]);

        $user = Auth::user();
        $existingVote = Vote::where('user_id', $user->id)
            ->where('reponse_id', $reponse->id)
            ->first();

        $voteStatus = null;

        if ($existingVote) {
            if ($existingVote->value === $validated['value']) {
                $existingVote->delete();
                $message = 'Vote removed successfully!';
            } else {
                $existingVote->update(['value' => $validated['value']]);
                $message = 'Vote updated successfully!';
                $voteStatus = $validated['value'];
            }
        } else {
            Vote::create([
                'value' => $validated['value'],
                'user_id' => $user->id,
                'reponse_id' => $reponse->id,
            ]);
            $message = 'Vote cast successfully!';
            $voteStatus = $validated['value'];
        }

        if ($request->wantsJson() || $request->ajax()) {
            // Calculate new score
            $upVotes = $reponse->votes()->where('value', 'up')->count();
            $downVotes = $reponse->votes()->where('value', 'down')->count();
            $score = $upVotes - $downVotes;

            return response()->json([
                'success' => true,
                'message' => $message,
                'score' => $score,
                'user_vote' => $voteStatus
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
