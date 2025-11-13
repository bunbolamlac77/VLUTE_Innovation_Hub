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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('banner_url')->nullable(); // Ảnh bìa cuộc thi
            $table->timestamp('start_date')->nullable(); // Ngày bắt đầu
            $table->timestamp('end_date')->nullable(); // Ngày kết thúc
            $table->enum('status', ['draft', 'open', 'judging', 'closed', 'archived'])
                ->default('draft');
            $table->foreignId('created_by')->constrained('users'); // User (Admin/TTĐMST) tạo
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
