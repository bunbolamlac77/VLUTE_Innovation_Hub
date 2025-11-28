<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Hiển thị trang chủ với ý tưởng nổi bật
     */
    public function index()
    {
        // Lấy các ý tưởng nổi bật (nhiều tim nhất và mới nhất)
        // Sắp xếp theo like_count giảm dần, sau đó theo created_at giảm dần
        // Lấy tối đa 4 ý tưởng để hiển thị
        $featuredIdeas = Idea::publicApproved()
            ->with(['faculty', 'category', 'owner', 'likes'])
            ->withCount('likes')
            ->orderBy('like_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Lấy 4 cuộc thi đang mở (đang chạy và chưa hết hạn)
        $openCompetitions = \App\Models\Competition::where('status', 'open')
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>', now());
            })
            ->orderBy('start_date', 'desc')
            ->take(4)
            ->get();

        return view('welcome', compact('featuredIdeas', 'openCompetitions'));
    }
}

