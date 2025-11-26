<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ideas')) {
            try {
                DB::statement('ALTER TABLE ideas ADD FULLTEXT ideas_fulltext (title, summary)');
            } catch (Throwable $e) {
                // Bỏ qua nếu DB không phải MySQL 8 hoặc đã có index
            }
        }
    }

    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE ideas DROP INDEX ideas_fulltext');
        } catch (Throwable $e) {
            // ignore
        }
    }
};

