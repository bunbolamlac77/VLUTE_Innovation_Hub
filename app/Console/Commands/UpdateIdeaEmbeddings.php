<?php

namespace App\Console\Commands;

use App\Models\Idea;
use App\Services\GroqService;
use Illuminate\Console\Command;

class UpdateIdeaEmbeddings extends Command
{
    protected $signature = 'ideas:update-embeddings 
                            {--force : Force update all ideas, even if they already have embeddings}
                            {--limit= : Limit number of ideas to process}';

    protected $description = 'Cập nhật embedding vector cho các ý tưởng để hỗ trợ tính năng Thợ săn giải pháp (AI Solution Hunter)';

    protected GroqService $groq;

    public function __construct(GroqService $groq)
    {
        parent::__construct();
        $this->groq = $groq;
    }

    public function handle()
    {
        $this->info('🚀 Bắt đầu cập nhật embedding cho các ý tưởng...');
        $this->newLine();

        // Kiểm tra API keys
        $geminiApiKey = env('GEMINI_API_KEY');
        $openaiApiKey = env('OPENAI_API_KEY');

        if (empty($geminiApiKey) && empty($openaiApiKey)) {
            $this->error('❌ Lỗi: Cần GEMINI_API_KEY hoặc OPENAI_API_KEY để tạo embedding.');
            $this->line('   💡 Vui lòng thêm một trong hai key vào file .env');
            $this->line('   - GEMINI_API_KEY: https://makersuite.google.com/app/apikey');
            $this->line('   - OPENAI_API_KEY: https://platform.openai.com/api-keys');
            return 1;
        }

        // Xác định dimension mong đợi
        $expectedDim = !empty($geminiApiKey) ? 768 : 1536;
        $apiName = !empty($geminiApiKey) ? 'Gemini' : 'OpenAI';
        
        $this->info("✅ Sử dụng {$apiName} API (dimension: {$expectedDim})");
        $this->newLine();

        // Lấy danh sách ý tưởng cần cập nhật
        $query = Idea::query();

        if (!$this->option('force')) {
            // Chỉ lấy ý tưởng chưa có embedding hoặc có embedding với dimension sai
            $query->where(function($q) use ($expectedDim) {
                $q->whereNull('embedding_vector')
                  ->orWhereRaw('JSON_LENGTH(embedding_vector) != ?', [$expectedDim]);
            });
        }

        $totalIdeas = $query->count();

        if ($totalIdeas === 0) {
            $this->info('✅ Tất cả ý tưởng đã có embedding vector đầy đủ!');
            if (!$this->option('force')) {
                $this->line('   💡 Sử dụng --force để cập nhật lại tất cả ý tưởng');
            }
            return 0;
        }

        $this->info("📊 Tìm thấy {$totalIdeas} ý tưởng cần cập nhật");
        
        if ($limit = $this->option('limit')) {
            $query->limit((int)$limit);
            $this->line("   ⚠️  Giới hạn xử lý: {$limit} ý tưởng");
        }

        $this->newLine();

        $ideas = $query->get();
        $total = $ideas->count();
        $count = 0;
        $failed = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar($total);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->setMessage('Đang xử lý...');
        $bar->start();

        foreach ($ideas as $idea) {
            try {
                // Tạo text từ title, summary, description và content
                $text = trim(
                    ($idea->title ?? '') . '. ' . 
                    ($idea->summary ?? '') . ' ' . 
                    ($idea->description ?? '') . ' ' . 
                    ($idea->content ?? '')
                );

                if (empty($text)) {
                    $bar->setMessage("Idea #{$idea->id}: Không có nội dung");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Tạo embedding
                $bar->setMessage("Idea #{$idea->id}: Đang tạo embedding...");
                $vec = $this->groq->generateEmbedding($text);

                if ($vec && is_array($vec) && !empty($vec)) {
                    $actualDim = count($vec);
                    
                    if ($actualDim === $expectedDim) {
                        // Lưu embedding vào database
                        $idea->update(['embedding_vector' => json_encode($vec)]);
                        $count++;
                        $bar->setMessage("Idea #{$idea->id}: ✅ Hoàn thành");
                    } else {
                        $bar->setMessage("Idea #{$idea->id}: ⚠️  Dimension không khớp ({$actualDim} != {$expectedDim})");
                        $failed++;
                    }
                } else {
                    $bar->setMessage("Idea #{$idea->id}: ❌ Không thể tạo embedding");
                    $failed++;
                }

                // Delay nhỏ để tránh rate limit
                usleep(200000); // 0.2 giây

            } catch (\Throwable $e) {
                $bar->setMessage("Idea #{$idea->id}: ❌ Lỗi - " . substr($e->getMessage(), 0, 50));
                $failed++;
                \Log::error("Lỗi khi tạo embedding cho Idea #{$idea->id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->setMessage('Hoàn thành!');
        $bar->finish();
        $this->newLine(2);

        // Hiển thị kết quả
        $this->info('📊 Kết quả:');
        $this->table(
            ['Trạng thái', 'Số lượng'],
            [
                ['✅ Thành công', $count],
                ['❌ Thất bại', $failed],
                ['⏭️  Bỏ qua', $skipped],
                ['📝 Tổng cộng', $total],
            ]
        );

        if ($count > 0) {
            $this->info("✨ Đã cập nhật thành công {$count} ý tưởng!");
            $this->line('   💡 Các ý tưởng này đã sẵn sàng cho tính năng "Thợ săn giải pháp"');
        }

        if ($failed > 0) {
            $this->warn("⚠️  Có {$failed} ý tưởng không thể cập nhật. Vui lòng kiểm tra log để biết chi tiết.");
        }

        if ($skipped > 0) {
            $this->line("ℹ️  Đã bỏ qua {$skipped} ý tưởng (không có nội dung)");
        }

        return 0;
    }
}

