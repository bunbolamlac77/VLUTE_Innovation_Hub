<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeminiService;
use App\Models\Idea;
use Illuminate\Http\Request;

class AIController extends Controller
{
    protected GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    // 1) Review Insight
    public function reviewInsight(Request $request)
    {
        $content = (string) $request->input('content', '');
        $prompt = "Bạn là Giám khảo cuộc thi khởi nghiệp. Hãy phân tích ý tưởng sau:\n\n\"{$content}\"\n\nHãy trả về nhận xét ngắn gọn định dạng Markdown gồm: **Điểm mạnh**, **Điểm yếu**, **Tiềm năng** (Thang 10).";
        return response()->json(['result' => $this->gemini->generateText($prompt)]);
    }

    // 2) Vision
    public function analyzeVisual(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120']);
        $path = $request->file('image')->getRealPath();
        $prompt = "Hãy nhìn vào Poster/Slide này.\n- Đánh giá tính thẩm mỹ (Màu sắc, bố cục).\n- Đánh giá nội dung hiển thị.\n- Đưa ra lời khuyên cải thiện ngắn gọn.";
        return response()->json(['result' => $this->gemini->analyzeImage($prompt, $path)]);
    }

    // 3) Check Duplicate by Embedding Cosine Similarity
    public function checkDuplicate(Request $request)
    {
        $currentText = (string) $request->input('content', '');
        $currentId = $request->input('current_id');
        $currentVector = $this->gemini->generateEmbedding($currentText);
        if (!$currentVector) {
            return response()->json(['error' => 'Không thể tạo Vector.'], 500);
        }

        $ideas = Idea::whereNotNull('embedding_vector')->get();
        $matches = [];
        foreach ($ideas as $idea) {
            if (!empty($currentId) && (string)$idea->id === (string)$currentId) {
                continue;
            }
            $storedVector = json_decode((string)$idea->embedding_vector, true);
            if (is_array($storedVector)) {
                $score = $this->cosineSimilarity($currentVector, $storedVector);
                if ($score >= 0.75) {
                    $matches[] = [
                        'title' => $idea->title ?? ('Ý tưởng #' . $idea->id),
                        'score' => round($score * 100, 1) . '%',
                    ];
                }
            }
        }

        // sort by numeric score desc
        usort($matches, function ($a, $b) {
            $sa = (float) rtrim($a['score'], '%');
            $sb = (float) rtrim($b['score'], '%');
            return $sb <=> $sa;
        });

        return response()->json([
            'is_duplicate' => count($matches) > 0,
            'matches' => array_slice($matches, 0, 3),
        ]);
    }

    private function cosineSimilarity(array $vecA, array $vecB): float
    {
        $dot = 0.0; $magA = 0.0; $magB = 0.0;
        $len = min(count($vecA), count($vecB));
        for ($i = 0; $i < $len; $i++) {
            $a = (float) $vecA[$i];
            $b = (float) $vecB[$i];
            $dot += $a * $b;
            $magA += $a * $a;
            $magB += $b * $b;
        }
        if ($magA <= 0 || $magB <= 0) return 0.0;
        return $dot / (sqrt($magA) * sqrt($magB));
    }

    // TOOL: Seed embeddings for older ideas
    public function seedEmbeddings()
    {
        $ideas = Idea::whereNull('embedding_vector')->limit(10)->get();
        $count = 0;
        foreach ($ideas as $idea) {
            $text = trim(($idea->title ?? '') . '. ' . ($idea->summary ?? '') . ' ' . ($idea->description ?? '') . ' ' . ($idea->content ?? ''));
            if ($text === '') continue;
            $vec = $this->gemini->generateEmbedding($text);
            if ($vec) {
                $idea->update(['embedding_vector' => json_encode($vec)]);
                $count++;
            }
        }
        return "Đã cập nhật vector cho {$count} ý tưởng.";
    }

    // --- TÍNH NĂNG A: KIẾN TRÚC SƯ CÔNG NGHỆ (CHO SINH VIÊN) ---
    public function suggestTechStack(Request $request)
    {
        $content = $request->input('content');

        $prompt = "Bạn là một CTO (Giám đốc công nghệ) giàu kinh nghiệm. Sinh viên có ý tưởng sau: \"$content\". \n" .
                  "Hãy đề xuất một bộ công nghệ (Tech Stack) phù hợp nhất để xây dựng dự án này. \n" .
                  "Chỉ trả về kết quả dưới dạng JSON (không có markdown) với cấu trúc sau: \n" .
                  "{" .
                  "\"frontend\": \"tên công nghệ + lý do ngắn\"," .
                  "\"backend\": \"tên công nghệ + lý do ngắn\"," .
                  "\"database\": \"tên công nghệ\"," .
                  "\"mobile\": \"tên công nghệ (nếu cần)\"," .
                  "\"hardware\": \"tên thiết bị (nếu là dự án IoT/Phần cứng)\"," .
                  "\"advice\": \"Lời khuyên triển khai ngắn gọn\"" .
                  "}";

        $resultRaw = $this->gemini->generateText($prompt);

        // Làm sạch chuỗi JSON (đề phòng AI trả về dạng ```json ... ```)
        $jsonString = str_replace(['```json', '```'], '', $resultRaw);

        return response()->json(['data' => json_decode($jsonString)]);
    }

    // --- TÍNH NĂNG B: THỢ SĂN GIẢI PHÁP (CHO DOANH NGHIỆP) ---
    public function scoutSolutions(Request $request)
    {
        $problem = $request->input('problem'); // Doanh nghiệp nhập "Vấn đề cần tìm giải pháp"

        // 1. Tạo vector cho vấn đề của doanh nghiệp
        $queryVector = $this->gemini->generateEmbedding($problem);

        if (!$queryVector) return response()->json(['message' => 'Lỗi tạo vector.'], 500);

        // 2. Quét toàn bộ kho ý tưởng (Tận dụng cột embedding_vector đã làm ở bài trước)
        $ideas = Idea::publicApproved()
                     ->whereNotNull('embedding_vector')
                     ->get();

        $matches = [];
        foreach ($ideas as $idea) {
            $ideaVector = json_decode($idea->embedding_vector);
            if (is_array($ideaVector)) {
                // Tái sử dụng hàm cosineSimilarity bạn đã viết ở bài trước
                $score = $this->cosineSimilarity($queryVector, $ideaVector);

                // Nếu độ phù hợp > 65% (Ngưỡng tìm kiếm ngữ nghĩa)
                if ($score >= 0.65) {
                    $matches[] = [
                        'id' => $idea->id,
                        'title' => $idea->title,
                        'slug' => $idea->slug,
                        'abstract' => \Illuminate\Support\Str::limit(\Illuminate\Support\Str::of(strip_tags($idea->summary ?? $idea->description ?? $idea->content ?? ''))->squish(), 140),
                        'author' => optional($idea->owner)->name ?? 'Ẩn danh', // Tác giả (owner)
                        'score' => round($score * 100, 1) // Điểm phù hợp
                    ];
                }
            }
        }

        // Sắp xếp: Phù hợp nhất lên đầu
        usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);

        return response()->json([
            'found' => count($matches),
            'results' => array_slice($matches, 0, 5) // Trả về top 5
        ]);
    }

    // DEBUG: Kiểm tra cấu hình API
    public function debugInfo()
    {
        $apiKey = env('GEMINI_API_KEY');
        return response()->json([
            'api_key_set' => !empty($apiKey),
            'api_key_length' => strlen($apiKey ?? ''),
            'api_key_preview' => !empty($apiKey) ? substr($apiKey, 0, 10) . '...' : 'NOT SET',
            'base_url' => 'https://generativelanguage.googleapis.com/v1beta/models/',
            'models' => [
                'text' => 'gemini-1.5-flash:generateContent',
                'vision' => 'gemini-1.5-flash:generateContent',
                'embedding' => 'text-embedding-004:embedContent'
            ]
        ]);
    }
}

