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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();       // Tiêu đề banner
            $table->string('image_path');              // Đường dẫn ảnh
            $table->string('link_url')->nullable();    // Link khi bấm vào (tuỳ chọn)
            $table->text('description')->nullable();   // Mô tả ngắn
            $table->integer('order')->default(0);      // Thứ tự hiển thị
            $table->boolean('is_active')->default(true); // Trạng thái ẩn/hiện
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
