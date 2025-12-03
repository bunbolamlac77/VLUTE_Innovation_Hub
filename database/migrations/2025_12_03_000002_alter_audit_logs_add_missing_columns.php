<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('audit_logs')) {
            return; // table will be created by the previous migration
        }

        Schema::table('audit_logs', function (Blueprint $table) {
            // Ensure required columns exist
            if (!Schema::hasColumn('audit_logs', 'action')) {
                $table->string('action')->after('id');
            }
            if (!Schema::hasColumn('audit_logs', 'actor_id')) {
                $table->unsignedBigInteger('actor_id')->nullable()->after('action');
            }
            if (!Schema::hasColumn('audit_logs', 'target_id')) {
                $table->unsignedBigInteger('target_id')->nullable()->after('actor_id');
            }
            if (!Schema::hasColumn('audit_logs', 'target_type')) {
                $table->string('target_type')->nullable()->after('target_id');
            }
            if (!Schema::hasColumn('audit_logs', 'meta')) {
                $table->json('meta')->nullable()->after('target_type');
            }
            if (!Schema::hasColumn('audit_logs', 'created_at')) {
                $table->timestamps();
            }
        });

        // Add foreign key for actor_id if not exists (best-effort)
        try {
            Schema::table('audit_logs', function (Blueprint $table) {
                // MySQL will error if FK exists; wrap in try/catch at runtime
                $table->foreign('actor_id')->references('id')->on('users')->nullOnDelete();
            });
        } catch (\Throwable $e) {
            // ignore if constraint already exists
        }

        // Add helpful indexes (best-effort)
        try {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->index(['target_id', 'target_type'], 'audit_logs_target_idx');
                $table->index('action', 'audit_logs_action_idx');
            });
        } catch (\Throwable $e) {
            // ignore if indexes already exist
        }
    }

    public function down(): void
    {
        // Non-destructive: just drop indexes/foreign if present, keep table/columns
        try {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->dropForeign(['actor_id']);
            });
        } catch (\Throwable $e) {}
        try {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->dropIndex('audit_logs_target_idx');
                $table->dropIndex('audit_logs_action_idx');
            });
        } catch (\Throwable $e) {}
    }
};

