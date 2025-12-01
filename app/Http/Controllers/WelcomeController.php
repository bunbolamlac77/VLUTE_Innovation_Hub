<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\User;
use App\Models\Competition;
use App\Models\Organization;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Hiển thị trang chủ với ý tưởng nổi bật & các số liệu tổng quan
     */
    public function index()
    {
        // Lấy các ý tưởng nổi bật (nhiều tim nhất và mới nhất)
        $featuredIdeas = Idea::publicApproved()
            ->with(['faculty', 'category', 'owner', 'likes'])
            ->withCount('likes')
            ->orderBy('like_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Query cuộc thi đang mở (đang chạy và chưa hết hạn)
        $openCompetitionsQuery = Competition::where('status', 'open')
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>', now());
            });

        $openCompetitionsCount = (clone $openCompetitionsQuery)->count();
        $openCompetitions = (clone $openCompetitionsQuery)
            ->orderBy('start_date', 'desc')
            ->take(4)
            ->get();

        // Các số liệu tổng quan
        $ideaCount = Idea::publicApproved()->count();

        // Mentor = user có role 'staff' hoặc có roles pivot chứa 'staff' hoặc 'reviewer'
        $mentorCount = User::where('role', 'staff')
            ->orWhereHas('roles', function ($q) {
                $q->whereIn('slug', ['staff', 'reviewer']);
            })
            ->count();

        // Đối tác: nếu có bảng organizations thì dùng, ngược lại fallback 13 (số logo hiện có)
        $partners = class_exists(Organization::class) ? Organization::count() : 0;
        $partnerCount = $partners > 0 ? $partners : 13;
        $awardCount = 17; // theo thiết kế hiện tại

        return view('welcome', compact(
            'featuredIdeas',
            'openCompetitions',
            'openCompetitionsCount',
            'ideaCount',
            'mentorCount',
            'partnerCount',
            'awardCount'
        ));
    }
}

