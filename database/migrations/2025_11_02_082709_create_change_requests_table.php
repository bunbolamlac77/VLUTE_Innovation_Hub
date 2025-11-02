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
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('reviews')->onDelete('cascade');
            $table->foreignId('idea_id')->constrained('ideas')->onDelete('cascade')->comment('Ý tưởng cần chỉnh sửa');
            $table->text('request_message')->comment('Nội dung yêu cầu chỉnh sửa');
            $table->boolean('is_resolved')->default(false)->comment('Đã được xử lý hay chưa');
            $table->timestamps();

            $table->index('review_id');
            $table->index('idea_id');
            $table->index('is_resolved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_requests');
    }
};
