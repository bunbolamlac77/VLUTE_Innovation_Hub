<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventsController extends Controller
{
    /**
     * Trang sự kiện/cuộc thi công khai (giống ngân hàng ý tưởng)
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q'));
        $status = $request->get('status'); // open|ended

        $query = Competition::query();

        // Tìm kiếm theo tiêu đề/mô tả
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Lọc theo trạng thái: chỉ còn 2 nhóm "đang mở" và "đã kết thúc"
        if ($status === 'ended') {
            // ĐÃ KẾT THÚC: đóng/lưu trữ hoặc đã qua hạn
            $query->where(function ($q) {
                $q->whereIn('status', ['closed', 'archived'])
                  ->orWhere(function ($x) {
                      $x->whereNotNull('end_date')->where('end_date', '<=', now());
                  });
            });
        } else {
            // ĐANG MỞ (mặc định): status=open và chưa hết hạn hoặc không đặt hạn
            $query->where('status', 'open')
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>', now());
                });
        }

        $competitions = $query->orderBy('start_date', 'desc')->paginate(12)->withQueryString();

        return view('events.index', compact('competitions', 'q', 'status'));
    }
}

