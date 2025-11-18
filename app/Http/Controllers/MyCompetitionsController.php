<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class MyCompetitionsController extends Controller
{
    /**
     * Hiển thị danh sách các cuộc thi mà user đã đăng ký.
     */
    public function index(Request $request): View
    {
        $registrations = Auth::user()->competitionRegistrations()
                            ->with('competition') // Lấy thông tin của cuộc thi
                            ->latest()
                            ->paginate(15);
                            
        return view('my-competitions.index', [
            'registrations' => $registrations,
        ]);
    }
}
