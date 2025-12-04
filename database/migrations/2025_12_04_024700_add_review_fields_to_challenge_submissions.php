<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('challenge_submissions')) {
            return;
        }

        Schema::table('challenge_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('challenge_submissions', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('submitted_at');
                $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('challenge_submissions', 'reviewed_at')) {
                $table->dateTime('reviewed_at')->nullable()->after('reviewed_by');
            }
            if (!Schema::hasColumn('challenge_submissions', 'score')) {
                $table->unsignedTinyInteger('score')->nullable()->after('reviewed_at');
            }
            if (!Schema::hasColumn('challenge_submissions', 'feedback')) {
                $table->text('feedback')->nullable()->after('score');
            }

            $table->index(['challenge_id', 'reviewed_by']);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('challenge_submissions')) {
            return;
        }

        Schema::table('challenge_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('challenge_submissions', 'reviewed_by')) {
                $table->dropForeign(['reviewed_by']);
                $table->dropColumn('reviewed_by');
            }
            if (Schema::hasColumn('challenge_submissions', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
            if (Schema::hasColumn('challenge_submissions', 'score')) {
                $table->dropColumn('score');
            }
            if (Schema::hasColumn('challenge_submissions', 'feedback')) {
                $table->dropColumn('feedback');
            }
        });
    }
};

