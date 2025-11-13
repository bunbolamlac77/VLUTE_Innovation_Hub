<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompetitionController extends Controller
{
    /**
     * Hiển thị danh sách các cuộc thi.
     */
    public function index(): View
    {
        $competitions = Competition::where('status', 'open') // Chỉ lấy cuộc thi đang 'open'
            ->where('end_date', '>', now()) // Chỉ lấy cuộc thi chưa hết hạn
            ->orderBy('start_date', 'desc')
            ->paginate(12);

        return view('competitions.index', [
            'competitions' => $competitions,
        ]);
    }

    /**
     * Hiển thị chi tiết một cuộc thi.
     */
    public function show(Competition $competition): View
    {
        // Tự động tìm cuộc thi bằng 'slug'
        // Load thêm thông tin người tạo
        $competition->load('createdBy');

        return view('competitions.show', [
            'competition' => $competition,
        ]);
    }
}
