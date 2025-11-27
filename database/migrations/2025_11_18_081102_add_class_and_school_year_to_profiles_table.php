<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'class_name')) {
                $table->string('class_name')->nullable()->after('student_code');
            }
            if (!Schema::hasColumn('profiles', 'school_year')) {
                $table->string('school_year')->nullable()->after('class_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'school_year')) {
                $table->dropColumn('school_year');
            }
            if (Schema::hasColumn('profiles', 'class_name')) {
                $table->dropColumn('class_name');
            }
        });
    }
};
