<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class CompetitionRegistrationController extends Controller
{
    /**
     * Lưu đơn đăng ký tham gia cuộc thi.
     */
    public function store(Request $request, Competition $competition): RedirectResponse
    {
        $user = Auth::user();

        // 1. Kiểm tra xem cuộc thi còn mở không
        if ($competition->status !== 'open' || $competition->end_date < now()) {
            return back()->with('error', 'Cuộc thi này đã đóng hoặc không còn nhận đăng ký.');
        }

        // 2. Kiểm tra xem user đã đăng ký chưa
        $existingRegistration = CompetitionRegistration::where('competition_id', $competition->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($existingRegistration) {
            return back()->with('info', 'Bạn đã đăng ký tham gia cuộc thi này rồi.');
        }

        // 3. (Tùy chọn) Lấy tên nhóm nếu có
        // $request->validate(['team_name' => 'nullable|string|max:255']);

        // 4. Tạo đơn đăng ký
        CompetitionRegistration::create([
            'competition_id' => $competition->id,
            'user_id' => $user->id,
            'team_name' => $request->input('team_name'), // (Sẽ thêm ô này ở Bước D)
            'status' => 'approved', // Tự động duyệt
        ]);

        // 5. Chuyển hướng lại với thông báo thành công
        return redirect()->route('competitions.show', $competition)
            ->with('success', 'Đăng ký tham gia cuộc thi thành công!');
    }
}
