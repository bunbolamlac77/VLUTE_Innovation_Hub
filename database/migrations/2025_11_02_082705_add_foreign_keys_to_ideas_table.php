<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
            // Chỉ thêm foreign key nếu bảng đã tồn tại
            // Lưu ý: Chạy sau khi các bảng faculties và categories đã được tạo
            if (Schema::hasTable('faculties')) {
                $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('set null');
            }
            if (Schema::hasTable('categories')) {
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->dropForeign(['category_id']);
        });
    }
};

