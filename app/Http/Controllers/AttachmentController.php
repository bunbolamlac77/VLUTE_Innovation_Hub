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

        // Lấy đối tượng cha (ví dụ: Idea)
        $idea = $attachment->attachable;

        // Kiểm tra quyền - chỉ cho phép download nếu user có quyền xem idea
        if (!$idea || !Auth::user()->can('view', $idea)) {
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
