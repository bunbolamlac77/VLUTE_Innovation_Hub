<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * Download file đính kèm
     */
    public function download($id)
    {
        $attachment = Attachment::findOrFail($id);

        // Lấy đối tượng cha (Idea, CompetitionSubmission, ...)
        $parent = $attachment->attachable;

        // Kiểm tra quyền theo loại đối tượng
        $user = Auth::user();
        $authorized = false;

        if ($parent instanceof \App\Models\Idea) {
            $authorized = $user && $user->can('view', $parent);
        } elseif ($parent instanceof \App\Models\CompetitionSubmission) {
            // Chủ đăng ký cuộc thi, hoặc admin/center/board có thể tải
            $regUserId = optional($parent->registration)->user_id;
            $authorized = $user && (
                $user->id === $regUserId ||
                $user->hasRole('admin') || $user->hasRole('center') || $user->hasRole('board')
            );
        } elseif ($parent instanceof \App\Models\ChallengeSubmission) {
            $authorized = $user && (
                $user->id === $parent->user_id ||
                ($user->profile && optional($parent->challenge)->organization_id === optional($user->profile)->organization_id) ||
                $user->hasRole('admin') || $user->hasRole('center') || $user->hasRole('board')
            );
        } elseif ($parent instanceof \App\Models\Challenge) {
            // Cho phép người dùng đã đăng nhập tải tài liệu public của Challenge
            $authorized = $user !== null;
        }

        if (!$authorized) {
            abort(403, 'Bạn không có quyền truy cập file này.');
        }

        // Kiểm tra file tồn tại
        $filePath = Storage::disk('local')->path($attachment->path);

        if (!file_exists($filePath)) {
            abort(404, 'File không tồn tại.');
        }

        // Trả về file để download
        return response()->download($filePath, $attachment->filename);
    }
}
