<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Remove lecturer-level statuses from ideas.status ENUM
        try {
            DB::statement("ALTER TABLE ideas MODIFY COLUMN status ENUM('draft','submitted_center','needs_change_center','approved_center','submitted_board','needs_change_board','approved_final','rejected') NOT NULL DEFAULT 'draft'");
        } catch (\Throwable $e) {
            // ignore for unsupported DBs (e.g., SQLite in dev)
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE ideas MODIFY COLUMN status ENUM('draft','submitted_gv','needs_change_gv','approved_gv','submitted_center','needs_change_center','approved_center','submitted_board','needs_change_board','approved_final','rejected') NOT NULL DEFAULT 'draft'");
        } catch (\Throwable $e) {
        }
    }
};

