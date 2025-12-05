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
            if (!Schema::hasColumn('challenge_submissions', 'full_name')) {
                $table->string('full_name')->nullable()->after('title');
            }
            if (!Schema::hasColumn('challenge_submissions', 'phone')) {
                $table->string('phone', 30)->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('challenge_submissions', 'address')) {
                $table->string('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('challenge_submissions', 'class_name')) {
                $table->string('class_name')->nullable()->after('address');
            }
            if (!Schema::hasColumn('challenge_submissions', 'school_name')) {
                $table->string('school_name')->nullable()->after('class_name');
            }
            if (!Schema::hasColumn('challenge_submissions', 'team_members')) {
                $table->text('team_members')->nullable()->after('school_name');
            }
            if (!Schema::hasColumn('challenge_submissions', 'mentor_name')) {
                $table->string('mentor_name')->nullable()->after('team_members');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('challenge_submissions')) {
            return;
        }
        Schema::table('challenge_submissions', function (Blueprint $table) {
            foreach (['mentor_name','team_members','school_name','class_name','address','phone','full_name'] as $col) {
                if (Schema::hasColumn('challenge_submissions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

