<?php

namespace App\Console\Commands;

use App\Services\GeminiService;
use Illuminate\Console\Command;

class TestGeminiApi extends Command
{
    protected $signature = 'gemini:test';
    protected $description = 'Test Gemini API configuration and connectivity';

    public function handle()
    {
        $this->info('🔍 Testing Gemini API Configuration...\n');

        // 1. Check API Key
        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            $this->error('❌ GEMINI_API_KEY not set in .env');
            $this->line('   Please add: GEMINI_API_KEY=your_key_here');
            return 1;
        }
        $this->info('✅ API Key is set');
        $this->line('   Preview: ' . substr($apiKey, 0, 10) . '...');

        // 2. Test Text Generation
        $this->line('\n📝 Testing Text Generation...');
        $gemini = app(GeminiService::class);
        $result = $gemini->generateText('Xin chào, bạn là ai?');
        
        if (str_contains($result, 'Lỗi')) {
            $this->error('❌ Text Generation Failed: ' . $result);
        } else {
            $this->info('✅ Text Generation Success');
            $this->line('   Response: ' . substr($result, 0, 100) . '...');
        }

        // 3. Test Embedding
        $this->line('\n🧮 Testing Embedding Generation...');
        $embedding = $gemini->generateEmbedding('Test text for embedding');
        
        if ($embedding === null) {
            $this->error('❌ Embedding Generation Failed');
        } else {
            $this->info('✅ Embedding Generation Success');
            $this->line('   Vector dimensions: ' . count($embedding));
        }

        $this->info('\n✨ All tests completed!');
        return 0;
    }
}

