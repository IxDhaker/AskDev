<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Reponse;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReponseSeeder extends Seeder
{
    public function run(): void
    {
        $questions = Question::all();
        $users = User::all();

        if ($questions->count() === 0 || $users->count() === 0) {
            return;
        }

        $responses = [
            'That is a great question! Here is how you do it...',
            'I encountered the same issue last week. Try this solution:',
            'Have you checked the documentation? It explains this clearly.',
            'You can achieve this by using the following code snippet:',
            'I recommend using a library for this instead of writing it from scratch.',
        ];

        foreach ($questions as $question) {
            // Add 0 to 3 responses per question
            $numResponses = rand(0, 3);

            for ($i = 0; $i < $numResponses; $i++) {
                Reponse::create([
                    'content' => $responses[array_rand($responses)] . "\n\nHope this helps!",
                    'question_id' => $question->id,
                    'user_id' => $users->random()->id,
                    'created_at' => now()->subDays(rand(0, 10)),
                ]);
            }
        }
    }
}
