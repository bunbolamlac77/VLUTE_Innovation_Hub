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
        // Skip nếu bảng đã tồn tại
        if (Schema::hasTable('ideas')) {
            return;
        }

        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade')->comment('Người tạo ý tưởng');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->comment('Mô tả ý tưởng');
            $table->text('summary')->nullable()->comment('Tóm tắt ý tưởng');
            $table->longText('content')->nullable()->comment('Nội dung chi tiết');
            $table->enum('status', [
                'draft',
                'submitted_gv',
                'needs_change_gv',
                'approved_gv',
                'submitted_center',
                'needs_change_center',
                'approved_center',
                'submitted_board',
                'needs_change_board',
                'approved_final',
                'rejected'
            ])->default('draft')->comment('Trạng thái trong luồng duyệt');
            $table->enum('visibility', ['private', 'team_only', 'public'])->default('private')->comment('Chế độ công khai');
            // Lưu ý: faculty_id và category_id sẽ thêm foreign key sau khi tạo bảng organizations và categories
            $table->unsignedBigInteger('faculty_id')->nullable()->comment('Khoa/Đơn vị (FK -> organizations)');
            $table->unsignedBigInteger('category_id')->nullable()->comment('Danh mục ý tưởng (FK -> categories)');
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('owner_id');
            $table->index('status');
            $table->index('visibility');
            $table->index('faculty_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};
