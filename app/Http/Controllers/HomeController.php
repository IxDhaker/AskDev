<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with(['user', 'reponses'])
            ->where('status', 'open');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $questions = $query->latest()->paginate(15);

        $stats = [
            'total_questions' => Question::count(),
            'total_answers' => \App\Models\Reponse::count(),
            'total_users' => User::count(),
        ];

        return view('home', compact('questions', 'stats'));
    }
}
