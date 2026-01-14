<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class VoteSeeder extends Seeder
{
    public function run(): void
    {
        $questions = Question::all();
        $users = User::all();

        if ($questions->count() === 0 || $users->count() === 0) {
            return;
        }

        foreach ($questions as $question) {
            $voters = $users->shuffle()->take(rand(0, $users->count()));

            foreach ($voters as $user) {
                if ($user->id !== $question->user_id) {
                    Vote::create([
                        'value' => rand(0, 1) ? 'up' : 'down',
                        'user_id' => $user->id,
                        'question_id' => $question->id,
                    ]);
                }
            }
        }
    }
}
