<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'department')) {
                $table->string('department')->nullable()->after('faculty_id');
            }
            if (!Schema::hasColumn('profiles', 'company_address')) {
                $table->string('company_address')->nullable()->after('company_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'company_address')) {
                $table->dropColumn('company_address');
            }
            if (Schema::hasColumn('profiles', 'department')) {
                $table->dropColumn('department');
            }
        });
    }
};

