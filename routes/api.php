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

// Test Gemini API without auth
Route::post('/test/gemini/text', function (Request $request) {
    $gemini = app(\App\Services\GeminiService::class);
    $prompt = $request->input('prompt', 'Xin chào');
    return response()->json([
        'result' => $gemini->generateText($prompt)
    ]);
});

Route::post('/test/gemini/image', function (Request $request) {
    $request->validate(['image' => 'required|image|max:5120']);
    $gemini = app(\App\Services\GeminiService::class);
    $path = $request->file('image')->getRealPath();
    $prompt = $request->input('prompt', 'Hãy mô tả ảnh này');
    return response()->json([
        'result' => $gemini->analyzeImage($prompt, $path)
    ]);
});

Route::get('/test/gemini/config', function () {
    $apiKey = env('GEMINI_API_KEY');
    return response()->json([
        'api_key_set' => !empty($apiKey),
        'api_key_length' => strlen($apiKey ?? ''),
        'api_key_preview' => !empty($apiKey) ? substr($apiKey, 0, 10) . '...' : 'NOT SET'
    ]);
});

