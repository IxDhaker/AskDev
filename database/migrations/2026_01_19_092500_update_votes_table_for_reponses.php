<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('votes', function (Blueprint $table) {
            // Drop the foreign key on user_id because it uses the unique index
            $table->dropForeign(['user_id']);

            // Drop the unique constraint
            $table->dropUnique(['user_id', 'question_id']);

            // Drop the foreign key on question_id before modification
            $table->dropForeign(['question_id']);
        });

        Schema::table('votes', function (Blueprint $table) {
            // Make question_id nullable
            $table->foreignId('question_id')->nullable()->change();

            // Add foreign key back for question_id
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');

            // Add reponse_id
            $table->foreignId('reponse_id')->nullable()->constrained()->onDelete('cascade');

            // Add user_id FK back
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Add new unique constraints
            // One vote per user per question
            $table->unique(['user_id', 'question_id']);
            // One vote per user per answer
            $table->unique(['user_id', 'reponse_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign(['reponse_id']);
            $table->dropUnique(['user_id', 'reponse_id']);
            $table->dropColumn('reponse_id');

            // Restore original state
            $table->dropUnique(['user_id', 'question_id']);
            $table->foreignId('question_id')->nullable(false)->change();
            $table->unique(['user_id', 'question_id']);
        });
    }
};
