<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->string('action');
                $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->unsignedBigInteger('target_id')->nullable();
                $table->string('target_type')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();

                $table->index(['target_id', 'target_type']);
                $table->index('action');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

