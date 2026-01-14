<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->count() === 0) {
            $this->command->info('No users found, skipping Question seeding.');
            return;
        }

        $titles = [
            'How to implement authentication in Laravel?',
            'What is the difference between Vue and React?',
            'Best practices for PHP dependency injection?',
            'How to fix CORS error in Axios?',
            'Deploying Laravel app to AWS EC2',
            'Understanding Python list comprehensions',
            'Dockerizing a MERN stack application',
            'SQL vs NoSQL: Which one to choose?',
            'How to center a div using CSS Flexbox?',
            'Explained: JavaScript Async/Await',
        ];

        foreach ($titles as $index => $title) {
            Question::create([
                'title' => $title,
                'content' => "I am struggling with " . strtolower($title) . ". \n\nCan someone explain how this works and provide some code examples? \n\nThanks in advance!",
                'status' => $index % 2 == 0 ? 'published' : 'pending',
                'user_id' => $users->random()->id,
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
