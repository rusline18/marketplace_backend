<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE listings ADD CONSTRAINT listings_status_check CHECK (status IN ('draft', 'pending_review', 'active', 'rejected', 'archived'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE listings DROP CONSTRAINT listings_status_check');
    }
};
