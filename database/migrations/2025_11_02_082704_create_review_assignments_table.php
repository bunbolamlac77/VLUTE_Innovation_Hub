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
        Schema::create('review_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idea_id')->constrained('ideas')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade')->comment('Người duyệt (GV, TTĐMST, BGH)');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete()->comment('Người phân công duyệt');
            $table->enum('review_level', ['gv', 'center', 'board'])->comment('Vòng duyệt: GV, Trung tâm, BGH');
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamps();

            $table->index('idea_id');
            $table->index('reviewer_id');
            $table->index('review_level');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_assignments');
    }
};
