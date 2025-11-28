<?php

namespace App\Http\Controllers;

use App\Models\CompetitionRegistration;
use App\Models\CompetitionSubmission;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompetitionSubmissionController extends Controller
{
    // Hiển thị form nộp bài
    public function create(CompetitionRegistration $registration)
    {
        // Đảm bảo người xem là người sở hữu đơn đăng ký
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }
        return view('my-competitions.submit', compact('registration'));
    }

    // Xử lý lưu bài nộp
    public function store(Request $request, CompetitionRegistration $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'file' => 'required|file|max:20480|mimes:pdf,doc,docx,zip,ppt,pptx', // 20MB max
        ]);

        // 1. Tạo Submission
        $submission = CompetitionSubmission::create([
            'registration_id' => $registration->id,
            'title' => $request->title,
            'abstract' => $request->abstract,
        ]);

        // 2. Lưu File (Sử dụng bảng Attachments có sẵn)
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . Str::random(10) . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('private/submissions', $filename, 'local');

            Attachment::create([
                'attachable_type' => CompetitionSubmission::class,
                'attachable_id' => $submission->id,
                'uploaded_by' => Auth::id(),
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return redirect()->route('my-competitions.index')
            ->with('status', 'Nộp bài dự thi thành công!');
    }
}

