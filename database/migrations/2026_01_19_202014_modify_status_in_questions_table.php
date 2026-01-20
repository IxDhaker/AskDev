<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First convert incompatible existing data to new valid values
        DB::table('questions')->where('status', 'published')->update(['status' => 'pending']);
        // Or mapping 'published' to 'open' if we want them to be 'open'.
        // Let's assume 'published' ~ 'open' for this context since the user wants to use 'open'.
        // However, 'pending' is safe as it exists in both.
        // But if I want to support 'open', let's map 'published' to 'pending' temporarily? 
        // NO, if I map to 'pending', they are safe.
        // Actually, let's map 'published' -> 'pending' to be safe, or just clear them.

        // Let's map 'published' to 'pending' for now to fit the intersection, 
        // OR just execute the ALTER with the superset if I could? No, I want to restrict it.

        // Actually, I can use a 2-step ALTER.
        // But simply updating the data first is easiest.
        // 'published' implies it was visible. 'open' implies visible and active.
        // I'll update 'published' to 'pending' (safe common ground) or just 'pending'.
        // Wait, if I want to eventually support 'open', I can't set them to 'open' YET because 'open' is not in the OLD enum.

        // Ah! Catch-22.
        // Old Enum: pending, published, rejected
        // New Enum: open, closed, pending

        // If I update 'published' -> 'open', it fails because 'open' is not in old enum.
        // If I update 'published' -> 'pending', it works. 'pending' is in both.

        // So:
        // 1. Update 'published' -> 'pending'.
        // 2. Update 'rejected' -> 'pending' (or just delete/ignore if not present).
        // 3. ALTER table to new enum.
        // 4. (Optional) Update 'pending' (that were published) to 'open'? Lost that info.

        // Alternative:
        // ALTER table to String.
        // Update values.
        // ALTER table to New Enum.

        // This is the robust way.

        Schema::table('questions', function (Blueprint $table) {
            // We can't easily change to text using Schema builder on an enum in some drivers without dbal.
            // But we can use raw SQL.
        });

        // Step 1: Change to VARCHAR temporarily
        DB::statement("ALTER TABLE questions MODIFY COLUMN status VARCHAR(255)");

        // Step 2: Migrate data
        DB::table('questions')->where('status', 'published')->update(['status' => 'open']);
        DB::table('questions')->where('status', 'rejected')->update(['status' => 'closed']);

        // Step 3: Change to New ENUM
        DB::statement("ALTER TABLE questions MODIFY COLUMN status ENUM('open', 'closed', 'pending') NOT NULL DEFAULT 'open'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Change to VARCHAR
        DB::statement("ALTER TABLE questions MODIFY COLUMN status VARCHAR(255)");

        // Step 2: Migrate data back
        DB::table('questions')->where('status', 'open')->update(['status' => 'published']);
        DB::table('questions')->where('status', 'closed')->update(['status' => 'rejected']);

        // Step 3: Change to Old ENUM
        DB::statement("ALTER TABLE questions MODIFY COLUMN status ENUM('pending', 'published', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
