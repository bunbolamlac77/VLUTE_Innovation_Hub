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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('review_assignments')->onDelete('cascade');
            $table->text('overall_comment')->nullable()->comment('Nhận xét tổng thể');
            $table->enum('decision', ['approved', 'needs_change', 'rejected'])->comment('Quyết định duyệt');
            $table->timestamps();

            $table->index('assignment_id');
            $table->index('decision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
