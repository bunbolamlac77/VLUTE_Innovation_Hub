<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add mentor role to idea_members.role_in_team ENUM
        // Use raw SQL to modify ENUM safely in MySQL
        try {
            DB::statement("ALTER TABLE idea_members MODIFY COLUMN role_in_team ENUM('owner','member','mentor') NOT NULL DEFAULT 'member'");
        } catch (\Throwable $e) {
            // Some drivers (e.g., SQLite in CI) don't support ENUM; fallback: try altering to string with check left as-is
            // No-op on unsupported platforms
        }

        // 2) Add role column to idea_invitations if not exists
        Schema::table('idea_invitations', function (Blueprint $table) {
            if (!Schema::hasColumn('idea_invitations', 'role')) {
                $table->enum('role', ['member', 'mentor'])->default('member')->after('email');
            }
        });

        // 3) Helpful indexes (idempotent-ish)
        try {
            DB::statement("CREATE INDEX IF NOT EXISTS idx_idea_inv_role ON idea_invitations (idea_id, email, role)");
        } catch (\Throwable $e) {
            // MySQL <8.0 doesn't support IF NOT EXISTS for indexes; ignore if exists
            try { DB::statement("CREATE INDEX idx_idea_inv_role ON idea_invitations (idea_id, email, role)"); } catch (\Throwable $e2) {}
        }
        try {
            DB::statement("CREATE INDEX IF NOT EXISTS idx_idea_members_role ON idea_members (role_in_team)");
        } catch (\Throwable $e) {
            try { DB::statement("CREATE INDEX idx_idea_members_role ON idea_members (role_in_team)"); } catch (\Throwable $e2) {}
        }

        // 4) Backfill statuses to new flow: map GV-related statuses forward to center
        try {
            DB::statement("UPDATE ideas SET status = 'submitted_center' WHERE status IN ('submitted_gv','approved_gv')");
            DB::statement("UPDATE ideas SET status = 'draft' WHERE status = 'needs_change_gv'");
        } catch (\Throwable $e) {
            // Ignore if table/column doesn't exist on some environments
        }
    }

    public function down(): void
    {
        // Drop role column on invitations
        Schema::table('idea_invitations', function (Blueprint $table) {
            if (Schema::hasColumn('idea_invitations', 'role')) {
                $table->dropColumn('role');
            }
        });

        // Revert ENUM (may fail if any 'mentor' values exist)
        try {
            DB::statement("ALTER TABLE idea_members MODIFY COLUMN role_in_team ENUM('owner','member') NOT NULL DEFAULT 'member'");
        } catch (\Throwable $e) {
            // No-op
        }

        // Do not attempt to reverse status backfill as it could be lossy.
    }
};

