<?php

namespace App\Http\Controllers\Enterprise;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;
use App\Models\ChallengeSubmission;
use Illuminate\Support\Facades\Auth;

class ChallengeManagerController extends Controller
{
    /**
     * Danh sách các Challenge do doanh nghiệp này tạo
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->profile || !$user->profile->organization_id) {
            return redirect()->route('profile.edit')
                ->with('error', 'Vui lòng cập nhật thông tin Doanh nghiệp (tổ chức) trong hồ sơ trước.');
        }

        $challenges = Challenge::where('organization_id', $user->profile->organization_id)
            ->withCount('submissions')
            ->latest('id')
            ->paginate(10);

        return view('enterprise.challenges.index', compact('challenges'));
    }

    /**
     * Form tạo Challenge mới
     */
    public function create()
    {
        return view('enterprise.challenges.create');
    }

    /**
     * Lưu Challenge mới
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->profile || !$user->profile->organization_id) {
            return redirect()->route('profile.edit')
                ->with('error', 'Vui lòng cập nhật thông tin Doanh nghiệp (tổ chức) trong hồ sơ trước.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'reward' => 'required|string|max:255',
            'deadline' => 'required|date|after:now',
        ]);

        Challenge::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'reward' => $validated['reward'],
            'deadline' => $validated['deadline'],
            'organization_id' => $user->profile->organization_id,
            'status' => 'open',
        ]);

        return redirect()->route('enterprise.challenges.index')
            ->with('status', 'Đã tạo Challenge thành công!');
    }

    /**
     * Xem chi tiết và danh sách bài giải của Sinh viên
     */
    public function show(Challenge $challenge)
    {
        $user = Auth::user();
        if (!$user || !$user->profile || $challenge->organization_id !== $user->profile->organization_id) {
            abort(403);
        }

        $challenge->load(['submissions.user', 'submissions.attachments', 'submissions.reviewer']);

        return view('enterprise.challenges.show', compact('challenge'));
    }

    /**
     * Đóng Challenge (ngừng nhận bài)
     */
    public function close(Challenge $challenge)
    {
        $user = Auth::user();
        if (!$user || !$user->profile || $challenge->organization_id !== $user->profile->organization_id) {
            abort(403);
        }

        if ($challenge->status !== 'closed') {
            $challenge->update(['status' => 'closed']);
        }

        return redirect()->route('enterprise.challenges.show', $challenge->id)
            ->with('status', 'Đã đóng challenge.');
    }

    /**
     * Mở lại Challenge (tiếp tục nhận bài)
     */
    public function reopen(Challenge $challenge)
    {
        $user = Auth::user();
        if (!$user || !$user->profile || $challenge->organization_id !== $user->profile->organization_id) {
            abort(403);
        }

        if ($challenge->status !== 'open') {
            $challenge->update(['status' => 'open']);
        }

        return redirect()->route('enterprise.challenges.show', $challenge->id)
            ->with('status', 'Đã mở lại challenge.');
    }

    /**
     * Chấm điểm/nhận xét một bài nộp
     */
    public function reviewSubmission(Request $request, Challenge $challenge, \App\Models\ChallengeSubmission $submission)
    {
        $user = Auth::user();
        if (!$user || !$user->profile || $challenge->organization_id !== $user->profile->organization_id) {
            abort(403);
        }

        // Đảm bảo submission thuộc về challenge này
        if ($submission->challenge_id !== $challenge->id) {
            abort(404);
        }

        $data = $request->validate([
            'score' => 'nullable|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $submission->fill([
            'score' => $data['score'] ?? null,
            'feedback' => $data['feedback'] ?? null,
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
        ])->save();

        return redirect()->route('enterprise.challenges.show', $challenge->id)
            ->with('status', 'Đã lưu đánh giá bài nộp.');
    }
}

