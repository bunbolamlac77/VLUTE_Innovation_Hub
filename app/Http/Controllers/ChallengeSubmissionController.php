<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChallengeSubmissionController extends Controller
{
    // Hiển thị form nộp bài cho một Challenge cụ thể
    public function create(Challenge $challenge)
    {
        // Chỉ cho nộp khi đang mở
        if ($challenge->status !== 'open') {
            return redirect()->route('challenges.show', $challenge)
                ->with('status', 'Challenge hiện không mở nộp bài.');
        }

        // Kiểm tra đã nộp chưa
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }

        $exists = ChallengeSubmission::where('challenge_id', $challenge->id)
            ->where('user_id', $userId)
            ->exists();
        if ($exists) {
            return redirect()->route('challenges.show', $challenge)
                ->with('status', 'Bạn đã nộp bài cho challenge này.');
        }

        return view('challenges.submit', compact('challenge'));
    }

    // Xử lý lưu bài nộp
    public function store(Request $request, Challenge $challenge)
    {
        if ($challenge->status !== 'open') {
            return redirect()->route('challenges.show', $challenge)
                ->with('status', 'Challenge hiện không mở nộp bài.');
        }

        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'solution_description' => 'nullable|string',
            'file' => 'required|file|max:30720|mimes:pdf,doc,docx,zip,ppt,pptx', // 30MB
        ]);

        // Tạo submission (unique per user x challenge)
        $submission = ChallengeSubmission::create([
            'challenge_id' => $challenge->id,
            'user_id' => $userId,
            'title' => $request->input('title'),
            'solution_description' => $request->input('solution_description'),
        ]);

        // Lưu file đính kèm (dùng bảng Attachments đã có)
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . Str::random(8) . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('private/challenge_submissions', $filename, 'local');

            Attachment::create([
                'attachable_type' => ChallengeSubmission::class,
                'attachable_id' => $submission->id,
                'uploaded_by' => $userId,
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return redirect()->route('challenges.show', $challenge)
            ->with('status', 'Nộp bài cho challenge thành công!');
    }
}

