<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('challenges')) {
            return; // bảng chưa có, migration tạo bảng gốc sẽ xử lý
        }

        Schema::table('challenges', function (Blueprint $table) {
            if (!Schema::hasColumn('challenges', 'problem_statement')) {
                $table->longText('problem_statement')->nullable()->after('description');
            }
            if (!Schema::hasColumn('challenges', 'requirements')) {
                $table->longText('requirements')->nullable()->after('problem_statement');
            }
            if (!Schema::hasColumn('challenges', 'image')) {
                $table->string('image')->nullable()->after('reward');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('challenges')) {
            return;
        }
        Schema::table('challenges', function (Blueprint $table) {
            if (Schema::hasColumn('challenges', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('challenges', 'requirements')) {
                $table->dropColumn('requirements');
            }
            if (Schema::hasColumn('challenges', 'problem_statement')) {
                $table->dropColumn('problem_statement');
            }
        });
    }
};

