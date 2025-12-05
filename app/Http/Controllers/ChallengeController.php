<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChallengeController extends Controller
{
    /**
     * Danh sách Challenges công khai
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q'));
        $status = $request->get('status'); // open|closed|draft (draft có thể ẩn nếu chỉ hiển thị open/closed)

        $query = Challenge::query()->with('organization');

        if ($q !== '') {
            $query->where(function ($s) use ($q) {
                $s->where('title', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if (\in_array($status, ['open','closed','draft'], true)) {
            $query->where('status', $status);
        } else {
            // Mặc định hiển thị đang mở
            $query->where('status', 'open');
        }

        $challenges = $query->latest('id')->paginate(12)->withQueryString();

        return view('challenges.index', compact('challenges', 'q', 'status'));
    }

    /**
     * Trang chi tiết Challenge
     */
    public function show(Challenge $challenge): View
    {
        $challenge->load(['organization', 'attachments']);
        return view('challenges.show', compact('challenge'));
    }
}

