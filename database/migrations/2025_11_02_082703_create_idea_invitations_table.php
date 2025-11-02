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
        Schema::create('idea_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idea_id')->constrained('ideas')->onDelete('cascade');
            $table->foreignId('invited_by')->constrained('users')->onDelete('cascade')->comment('Người gửi lời mời');
            $table->string('email')->comment('Email người được mời');
            $table->string('token')->unique()->comment('Token xác nhận lời mời');
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamp('expires_at')->nullable()->comment('Thời gian hết hạn token');
            $table->timestamps();

            $table->index('idea_id');
            $table->index('invited_by');
            $table->index('email');
            $table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idea_invitations');
    }
};
