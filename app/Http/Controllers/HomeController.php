<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $questions = Question::with(['user', 'reponses'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total_questions' => Question::count(),
            'total_answers' => \App\Models\Reponse::count(),
            'total_users' => User::count(),
        ];

        return view('home', compact('questions', 'stats'));
    }
}
