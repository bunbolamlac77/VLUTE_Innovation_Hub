<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AIController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes for testing
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Test Groq API without auth
Route::post('/test/groq/text', function (Request $request) {
    $groq = app(\App\Services\GroqService::class);
    $prompt = $request->input('prompt', 'Xin chào');
    return response()->json([
        'result' => $groq->generateText($prompt)
    ]);
});

Route::post('/test/groq/image', function (Request $request) {
    $request->validate(['image' => 'required|image|max:5120']);
    $groq = app(\App\Services\GroqService::class);
    $path = $request->file('image')->getRealPath();
    $prompt = $request->input('prompt', 'Hãy mô tả ảnh này');
    return response()->json([
        'result' => $groq->analyzeImage($prompt, $path)
    ]);
});

Route::get('/test/groq/config', function () {
    $groqApiKey = env('GROQ_API_KEY');
    $openaiApiKey = env('OPENAI_API_KEY');
    return response()->json([
        'groq_api_key_set' => !empty($groqApiKey),
        'groq_api_key_length' => strlen($groqApiKey ?? ''),
        'groq_api_key_preview' => !empty($groqApiKey) ? substr($groqApiKey, 0, 10) . '...' : 'NOT SET',
        'groq_model' => env('GROQ_MODEL', 'llama-3.1-70b-versatile'),
        'openai_api_key_set' => !empty($openaiApiKey),
        'openai_api_key_preview' => !empty($openaiApiKey) ? substr($openaiApiKey, 0, 10) . '...' : 'NOT SET (Groq không hỗ trợ embedding - chỉ cần OpenAI nếu muốn dùng)',
        'note' => 'OpenAI API key cần thiết cho tính năng embedding (tìm kiếm ngữ nghĩa)'
    ]);
});

