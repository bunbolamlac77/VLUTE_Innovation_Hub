<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('idea_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idea_id')->constrained('ideas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->enum('visibility', ['team_only', 'public'])->default('team_only');
            $table->timestamps();

            $table->index(['idea_id', 'visibility']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('idea_comments');
    }
};

