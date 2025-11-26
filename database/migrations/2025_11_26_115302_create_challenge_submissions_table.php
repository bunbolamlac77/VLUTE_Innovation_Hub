<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('challenge_submissions')) {
            return;
        }

        Schema::create('challenge_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('challenge_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('idea_id')->nullable();
            $table->string('title');
            $table->text('solution_description')->nullable();
            $table->dateTime('submitted_at')->useCurrent();
            $table->timestamps();

            $table->foreign('challenge_id')->references('id')->on('challenges')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idea_id')->references('id')->on('ideas')->onDelete('set null');
            $table->unique(['challenge_id', 'user_id']);
            $table->index(['challenge_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenge_submissions');
    }
};

