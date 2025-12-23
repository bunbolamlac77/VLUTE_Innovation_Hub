<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->enum('approval_level', ['pending_center', 'pending_board', 'approved_center', 'approved_final', null])
                ->nullable()
                ->after('status')
                ->comment('Cấp duyệt: pending_center (chờ TT ĐMST), pending_board (chờ BGH), approved_center (đã duyệt TT), approved_final (đã duyệt cuối)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn('approval_level');
        });
    }
};
