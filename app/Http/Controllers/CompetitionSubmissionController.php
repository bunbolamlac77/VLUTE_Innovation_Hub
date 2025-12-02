<?php

namespace App\Http\Controllers;

use App\Models\CompetitionRegistration;
use App\Models\CompetitionSubmission;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CompetitionSubmissionController extends Controller
{
    // Hiển thị form chỉnh sửa bài nộp
    public function edit(CompetitionSubmission $submission)
    {
        $registration = $submission->registration;
        if (!$registration || $registration->user_id !== Auth::id()) {
            abort(403);
        }
        $competition = $registration->competition;
        if (!$competition || $competition->status !== 'open' || ($competition->end_date && $competition->end_date->isPast())) {
            return redirect()->route('my-competitions.index')
                ->with('error', 'Cuộc thi này đã đóng hoặc đã hết hạn nộp bài.');
        }

        $submission->load('attachments');

        return view('my-competitions.edit-submission', [
            'registration' => $registration,
            'competition' => $competition,
            'submission' => $submission,
        ]);
    }

    // Cập nhật bài nộp
    public function update(Request $request, CompetitionSubmission $submission)
    {
        $registration = $submission->registration;
        if (!$registration || $registration->user_id !== Auth::id()) {
            abort(403);
        }
        $competition = $registration->competition;
        if (!$competition || $competition->status !== 'open' || ($competition->end_date && $competition->end_date->isPast())) {
            return redirect()->route('my-competitions.index')
                ->with('error', 'Cuộc thi này đã đóng hoặc đã hết hạn nộp bài.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'file|max:20480|mimes:pdf,doc,docx,zip,ppt,pptx',
            'remove_attachments' => 'nullable|array',
            'remove_attachments.*' => 'integer',
        ]);

        $submission->update([
            'title' => $request->title,
            'abstract' => $request->abstract,
        ]);

        // Xoá file được chọn
        $toRemove = collect($request->input('remove_attachments', []))->filter();
        if ($toRemove->isNotEmpty()) {
            $attachments = $submission->attachments()->whereIn('id', $toRemove)->get();
            foreach ($attachments as $att) {
                if ($att->path && Storage::disk('local')->exists($att->path)) {
                    Storage::disk('local')->delete($att->path);
                }
                $att->delete();
            }
        }

        // Thêm file mới
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
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
        }

        return redirect()->route('competitions.submit.edit', $submission)
            ->with('success', 'Cập nhật bài nộp thành công.');
    }

    // Xoá bài nộp
    public function destroy(CompetitionSubmission $submission)
    {
        $registration = $submission->registration;
        if (!$registration || $registration->user_id !== Auth::id()) {
            abort(403);
        }

        // Xoá tất cả attachments
        $attachments = $submission->attachments()->get();
        foreach ($attachments as $att) {
            if ($att->path && Storage::disk('local')->exists($att->path)) {
                Storage::disk('local')->delete($att->path);
            }
            $att->delete();
        }

        $submission->delete();

        return redirect()->route('competitions.submit.create', $registration)
            ->with('success', 'Đã xoá bài nộp.');
    }

    // Hiển thị form nộp bài
    public function create(CompetitionRegistration $registration)
    {
        // Đảm bảo người xem là người sở hữu đơn đăng ký
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        // Chặn nộp nếu cuộc thi đã đóng hoặc hết hạn
        $competition = $registration->competition()->first();
        if (!$competition || $competition->status !== 'open' || ($competition->end_date && $competition->end_date->isPast())) {
            return redirect()->route('my-competitions.index')
                ->with('error', 'Cuộc thi này đã đóng hoặc đã hết hạn nộp bài.');
        }

        $submissions = $registration->submissions()->with('attachments')->latest()->get();
        return view('my-competitions.submit', [
            'registration' => $registration,
            'competition' => $competition,
            'submissions' => $submissions,
        ]);
    }

    // Xử lý lưu bài nộp
    public function store(Request $request, CompetitionRegistration $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        // Chặn nộp nếu cuộc thi đã đóng hoặc hết hạn
        $competition = $registration->competition()->first();
        if (!$competition || $competition->status !== 'open' || ($competition->end_date && $competition->end_date->isPast())) {
            return redirect()->route('my-competitions.index')
                ->with('error', 'Cuộc thi này đã đóng hoặc đã hết hạn nộp bài.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'files' => 'required|array','files.*' => 'file|max:20480|mimes:pdf,doc,docx,zip,ppt,pptx'
        ]);

        // 1. Tạo Submission
        $submission = CompetitionSubmission::create([
            'registration_id' => $registration->id,
            'title' => $request->title,
            'abstract' => $request->abstract,
        ]);

        // 2. Lưu nhiều file (Sử dụng bảng Attachments có sẵn)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
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
        }

        return redirect()->route('my-competitions.index')
            ->with('success', 'Nộp bài dự thi thành công!');
    }
}

