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
        Schema::create('competition_submissions', function (Blueprint $table) {
            $table->id();
            // Liên kết với đơn đăng ký
            $table->foreignId('registration_id')->constrained('competition_registrations')->cascadeOnDelete();
            $table->string('title'); // Tiêu đề bài nộp
            $table->text('abstract')->nullable(); // Tóm tắt bài nộp
            // (Chúng ta sẽ dùng bảng 'attachments' để upload file)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_submissions');
    }
};
