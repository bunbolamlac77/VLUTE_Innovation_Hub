<?php

namespace App\Http\Controllers\Enterprise;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;
use App\Models\ChallengeSubmission;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class ChallengeManagerController extends Controller
{
    /**
     * Đảm bảo user Doanh nghiệp có organization_id; nếu chưa có thì tự tạo/gán.
     */
    private function ensureOrganizationId($user): ?int
    {
        if (!$user) return null;
        // Tạo bản ghi profile nếu chưa có
        if (!$user->profile) {
            $user->profile()->create([]);
            $user->refresh();
        }
        $profile = $user->profile;
        if ($profile->organization_id) {
            return (int) $profile->organization_id;
        }
        // Tạo Organization mới dựa trên company_name hoặc tên user
        $name = trim((string) ($profile->company_name ?: ($user->name . ' - Doanh nghiệp')));
        $org = Organization::create([
            'name' => $name,
            'type' => 'company',
            'contact_email' => $user->email,
        ]);
        $profile->organization_id = $org->id;
        $profile->save();
        return (int) $org->id;
    }

    /**
     * Danh sách các Challenge do doanh nghiệp này tạo
     */
    public function index()
    {
        $user = Auth::user();
        $orgId = $this->ensureOrganizationId($user);

        if (!$orgId) {
            return redirect()->route('profile.edit')
                ->with('error', 'Vui lòng cập nhật thông tin Doanh nghiệp (tổ chức) trong hồ sơ trước.');
        }

        $challenges = Challenge::where('organization_id', $orgId)
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

        // Đảm bảo doanh nghiệp đã có Organization gắn với profile (tự tạo nếu thiếu)
        $orgId = $this->ensureOrganizationId($user);
        if (!$orgId) {
            return redirect()->route('profile.edit')
                ->with('error', 'Vui lòng cập nhật thông tin Doanh nghiệp (tổ chức) trong hồ sơ trước.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'problem_statement' => 'nullable|string',
            'requirements' => 'nullable|string',
            'reward' => 'required|string|max:255',
            'deadline' => 'required|date|after:now',
            'image' => 'nullable|image|max:2048', // 2MB
            'attachments' => 'nullable',
            'attachments.*' => 'file|mimes:pdf,csv,xls,xlsx,zip,doc,docx,ppt,pptx,txt,json|max:51200', // 50MB mỗi tệp
        ]);

        $challenge = new Challenge([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'problem_statement' => $validated['problem_statement'] ?? null,
            'requirements' => $validated['requirements'] ?? null,
            'reward' => $validated['reward'],
            'deadline' => $validated['deadline'],
            'organization_id' => $orgId,
            'status' => 'open',
        ]);

        // Upload ảnh bìa (lưu public)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . \Illuminate\Support\Str::random(8) . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/challenges', $filename, 'local');
            // Lưu đường dẫn rút gọn để sử dụng asset('storage/...')
            $challenge->image = str_replace('public/', '', $path);
        }

        $challenge->save();

        // Upload các tệp đính kèm (lưu private)
        if ($request->hasFile('attachments')) {
            foreach ((array) $request->file('attachments') as $file) {
                if (!$file) continue;
                $filename = time() . '_' . \Illuminate\Support\Str::random(8) . '_' . $file->getClientOriginalName();
                $stored = $file->storeAs('private/challenges', $filename, 'local');

                \App\Models\Attachment::create([
                    'attachable_type' => Challenge::class,
                    'attachable_id' => $challenge->id,
                    'uploaded_by' => $user->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $stored,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

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

        $challenge->load(['attachments', 'submissions.user', 'submissions.attachments', 'submissions.reviewer']);

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

