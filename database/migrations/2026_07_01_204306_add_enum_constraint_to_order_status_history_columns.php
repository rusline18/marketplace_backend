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
        DB::statement("ALTER TABLE order_status_history ADD CONSTRAINT order_status_history_from_status_check CHECK (from_status IN ('pending', 'confirmed', 'cancelled'))");
        DB::statement("ALTER TABLE order_status_history ADD CONSTRAINT order_status_history_to_status_check CHECK (to_status IN ('pending', 'confirmed', 'cancelled'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE order_status_history DROP CONSTRAINT order_status_history_from_status_check');
        DB::statement('ALTER TABLE order_status_history DROP CONSTRAINT order_status_history_to_status_check');
    }
};
