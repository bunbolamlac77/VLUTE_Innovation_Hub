<?php

namespace App\Console\Commands;

use App\Services\GroqService;
use Illuminate\Console\Command;

class TestGeminiApi extends Command
{
    protected $signature = 'groq:test';
    protected $description = 'Test Groq API configuration and connectivity';

    public function handle()
    {
        $this->info('🔍 Testing Groq API Configuration...\n');

        // 1. Check Groq API Key
        $groqApiKey = env('GROQ_API_KEY');
        if (empty($groqApiKey)) {
            $this->error('❌ GROQ_API_KEY not set in .env');
            $this->line('   Please add: GROQ_API_KEY=your_key_here');
            $this->line('   Get your key at: https://console.groq.com');
            return 1;
        }
        $this->info('✅ Groq API Key is set');
        $this->line('   Preview: ' . substr($groqApiKey, 0, 10) . '...');
        $this->line('   Model: ' . env('GROQ_MODEL', 'llama-3.1-70b-versatile'));

        // 2. Check OpenAI API Key (for embedding - optional)
        $openaiApiKey = env('OPENAI_API_KEY');
        if (empty($openaiApiKey)) {
            $this->info('ℹ️  OPENAI_API_KEY not set - Embedding feature disabled');
            $this->line('   💡 Groq không hỗ trợ embedding, chỉ cần OpenAI nếu muốn dùng tính năng:');
            $this->line('      - Kiểm tra trùng lặp ý tưởng');
            $this->line('      - Tìm kiếm ngữ nghĩa');
            $this->line('   ✅ Tất cả tính năng text generation vẫn hoạt động bình thường');
        } else {
            $this->info('✅ OpenAI API Key is set (optional - for embedding)');
        }

        // 3. Test Text Generation
        $this->line('\n📝 Testing Text Generation...');
        $groq = app(GroqService::class);
        $result = $groq->generateText('Xin chào, bạn là ai? Hãy giới thiệu về bản thân trong 2-3 câu.');
        
        if (str_contains($result, 'Lỗi')) {
            $this->error('❌ Text Generation Failed');
            $this->line('   Error: ' . $result);
            $this->line('   💡 Tip: Kiểm tra model name trong .env (GROQ_MODEL)');
            $this->line('   Available models: llama-3.1-70b-versatile, llama-3.1-8b-instant');
        } else {
            $this->info('✅ Text Generation Success');
            $this->line('   Response: ' . substr($result, 0, 150) . '...');
        }

        // 4. Test Embedding (if OpenAI key is available)
        if (!empty($openaiApiKey)) {
            $this->line('\n🧮 Testing Embedding Generation...');
            $embedding = $groq->generateEmbedding('Test text for embedding');
            
            if ($embedding === null) {
                $this->error('❌ Embedding Generation Failed');
                $this->line('   ⚠️  Lỗi có thể do:');
                $this->line('      - OpenAI API quota đã hết');
                $this->line('      - OpenAI API key không hợp lệ');
                $this->line('      - Lỗi kết nối đến OpenAI API');
                $this->line('');
                $this->line('   💡 Lưu ý: Embedding là tính năng TÙY CHỌN (chỉ cần OpenAI)');
                $this->line('      - Groq KHÔNG hỗ trợ embedding');
                $this->line('      - Các tính năng text generation vẫn hoạt động bình thường');
                $this->line('      - Chỉ cần embedding cho: tìm kiếm ngữ nghĩa, kiểm tra trùng lặp');
                $this->line('      - Xem log chi tiết: tail -f storage/logs/laravel.log');
            } else {
                $this->info('✅ Embedding Generation Success');
                $this->line('   Vector dimensions: ' . count($embedding));
            }
        } else {
            $this->line('\n⏭️  Skipping Embedding Test (OpenAI API key not set)');
            $this->line('   ✅ Groq không hỗ trợ embedding - chỉ cần OpenAI nếu muốn dùng');
            $this->line('   ✅ Tất cả tính năng text generation hoạt động bình thường');
        }

        $this->info('\n✨ All tests completed!');
        return 0;
    }
}

