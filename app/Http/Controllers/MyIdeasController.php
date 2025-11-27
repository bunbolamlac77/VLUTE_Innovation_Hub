<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\IdeaInvitation;
use App\Models\IdeaMember;
use App\Models\Category;
use App\Models\Faculty;
use App\Models\User;
use App\Models\Attachment;
use App\Mail\IdeaInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MyIdeasController extends Controller
{
    /**
     * Hiển thị danh sách ý tưởng của người dùng
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Lấy ý tưởng mà user là owner hoặc là member
        $ideas = Idea::where(function ($query) use ($user) {
            $query->where('owner_id', $user->id)
                ->orWhereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
        })
            ->with(['owner', 'faculty', 'category', 'members.user'])
            ->latest()
            ->paginate(12);

        return view('my-ideas.index', compact('ideas'));
    }

    /**
     * Hiển thị form tạo ý tưởng mới
     */
    public function create()
    {
        $faculties = Faculty::orderBy('sort_order')->orderBy('name')->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('my-ideas.create', compact('faculties', 'categories'));
    }

    /**
     * Lưu ý tưởng mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:50'],
            'content' => ['nullable', 'string'],
            'visibility' => ['required', Rule::in(['private', 'team_only', 'public'])],
            'faculty_id' => ['nullable', 'exists:faculties,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf,doc,docx,zip', 'max:10240'], // 10MB = 10240 KB
        ]);

        // Tạo slug từ title
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;

        // Đảm bảo slug là unique
        while (Idea::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $idea = Idea::create([
            'owner_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'content' => $validated['content'] ?? null,
            'visibility' => $validated['visibility'],
            'faculty_id' => $validated['faculty_id'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'status' => 'draft', // Mặc định là Draft
        ]);

        // Xử lý upload file đính kèm
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                try {
                    // Kiểm tra lại kích thước file (đảm bảo an toàn)
                    $maxSize = 10 * 1024 * 1024; // 10MB
                    if ($file->getSize() > $maxSize) {
                        continue; // Bỏ qua file vượt quá kích thước
                    }

                    // Tạo tên file duy nhất
                    $filename = time() . '_' . Str::random(10) . '_' . $file->getClientOriginalName();

                    // Lưu file vào storage/app/private/attachments
                    $path = $file->storeAs('private/attachments', $filename, 'local');

                    // Tạo bản ghi attachment
                    Attachment::create([
                        'attachable_type' => Idea::class,
                        'attachable_id' => $idea->id,
                        'uploaded_by' => Auth::id(),
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                } catch (\Exception $e) {
                    // Log lỗi nhưng không dừng quá trình tạo ý tưởng
                    \Log::error('Failed to upload attachment: ' . $e->getMessage());
                    continue;
                }
            }
        }

        return redirect()->route('my-ideas.show', $idea->id)
            ->with('status', 'Ý tưởng đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết ý tưởng
     */
    public function show(Idea $idea)
    {
        $idea->load([
            'owner',
            'faculty',
            'category',
            'members.user',
            'invitations',
            'reviewAssignments' => function ($query) {
                $query->with(['reviewer', 'review.changeRequests']);
            },
            'changeRequests' => function ($query) {
                $query->with(['review.assignment.reviewer'])->latest();
            },
            'attachments.uploader',
            'comments.user'
        ]);

        $user = Auth::user();

        // Kiểm tra quyền chỉnh sửa (sử dụng Policy)
        $canEdit = $user->can('update', $idea);

        // Kiểm tra quyền xóa (sử dụng Policy)
        $canDelete = $user->can('delete', $idea);

        // Kiểm tra quyền mời thành viên (sử dụng Policy)
        $canInvite = $user->can('invite', $idea);

        return view('my-ideas.show', compact('idea', 'canEdit', 'canDelete', 'canInvite'));
    }

    /**
     * Hiển thị form chỉnh sửa ý tưởng
     */
    public function edit(Idea $idea)
    {

        $faculties = Faculty::orderBy('sort_order')->orderBy('name')->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('my-ideas.edit', compact('idea', 'faculties', 'categories'));
    }

    /**
     * Cập nhật ý tưởng
     */
    public function update(Request $request, Idea $idea)
    {

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:50'],
            'content' => ['nullable', 'string'],
            'visibility' => ['required', Rule::in(['private', 'team_only', 'public'])],
            'faculty_id' => ['nullable', 'exists:faculties,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf,doc,docx,zip', 'max:10240'], // 10MB max
        ]);

        // Tạo slug mới nếu title thay đổi
        if ($idea->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $counter = 1;

            while (Idea::where('slug', $slug)->where('id', '!=', $idea->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $validated['slug'] = $slug;
        }

        $idea->update($validated);

        // Xử lý upload file đính kèm mới
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Tạo tên file duy nhất
                $filename = time() . '_' . Str::random(10) . '_' . $file->getClientOriginalName();

                // Lưu file vào storage/app/private/attachments
                $path = $file->storeAs('private/attachments', $filename, 'local');

                // Tạo bản ghi attachment
                Attachment::create([
                    'attachable_type' => Idea::class,
                    'attachable_id' => $idea->id,
                    'uploaded_by' => Auth::id(),
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('my-ideas.show', $idea->id)
            ->with('status', 'Ý tưởng đã được cập nhật thành công!');
    }

    /**
     * Xóa ý tưởng (chỉ khi là Draft)
     */
    public function destroy(Idea $idea)
    {

        $idea->delete();

        return redirect()->route('my-ideas.index')
            ->with('status', 'Ý tưởng đã được xóa thành công!');
    }

    /**
     * Nộp ý tưởng (chuyển từ Draft sang Submitted)
     */
    public function submit(Idea $idea)
    {
        // Optional: require at least 1 mentor to submit
        if (config('ideas.require_mentor_to_submit')) {
            if (!$idea->members()->where('role_in_team', 'mentor')->exists()) {
                return redirect()->route('my-ideas.show', $idea->id)
                    ->with('error', 'Vui lòng mời ít nhất 1 Giảng viên làm Cố vấn (Mentor) trước khi nộp.');
            }
        }

        // Map all submissions to center directly
        if ($idea->needsChange()) {
            if (in_array($idea->status, ['needs_change_center', 'needs_change_board'], true)) {
                $idea->status = $idea->status === 'needs_change_board' ? 'submitted_board' : 'submitted_center';
            } else {
                // includes legacy needs_change_gv
                $idea->status = 'submitted_center';
            }
        } else {
            // Draft -> Center directly
            $idea->status = 'submitted_center';
        }

        $idea->save();

        return redirect()->route('my-ideas.show', $idea->id)
            ->with('status', 'Nộp ý tưởng thành công! Hồ sơ đã được gửi đến Trung tâm ĐMST.');
    }

    /**
     * Mời thành viên vào ý tưởng
     */
    public function invite(Request $request, Idea $idea)
    {

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role'  => ['nullable', 'in:member,mentor'],
        ]);

        $email = strtolower($validated['email']);
        $role = $validated['role'] ?? 'member';

        // Nếu mời mentor: chỉ chủ sở hữu mới được mời (an toàn nghiệp vụ)
        if ($role === 'mentor' && Auth::id() !== $idea->owner_id) {
            return redirect()->route('my-ideas.show', $idea->id)
                ->with('error', 'Chỉ chủ sở hữu ý tưởng mới được mời Giảng viên làm Cố vấn.');
        }

        // Nếu mời mentor: ràng buộc domain và số lượng tối đa
        if ($role === 'mentor') {
            if (!str_ends_with($email, '@vlute.edu.vn')) {
                return redirect()->route('my-ideas.show', $idea->id)
                    ->with('error', 'Chỉ mời Mentor với email @vlute.edu.vn.');
            }
            // Giới hạn số mentor
            $maxMentors = (int) config('ideas.max_mentors', 3);
            $mentorCount = $idea->members()->where('role_in_team', 'mentor')->count();
            if ($mentorCount >= $maxMentors) {
                return redirect()->route('my-ideas.show', $idea->id)
                    ->with('error', 'Số lượng Mentor đã đạt giới hạn (' . $maxMentors . ').');
            }
        }

        // Kiểm tra user có tồn tại không
        $invitedUser = User::where('email', $email)->first();

        // Nếu có user và role=mentor: kiểm tra vai trò hợp lệ
        if ($role === 'mentor' && $invitedUser) {
            if (!$invitedUser->hasRole('staff')) {
                return redirect()->route('my-ideas.show', $idea->id)
                    ->with('error', 'Tài khoản này không phải Giảng viên.');
            }
        }

        // Kiểm tra đã là member chưa
        if (
            $invitedUser && $idea->members->contains(function ($member) use ($invitedUser) {
                return $member->user_id === $invitedUser->id;
            })
        ) {
            return redirect()->route('my-ideas.show', $idea->id)
                ->with('error', 'Người dùng này đã thuộc nhóm.');
        }

        // Kiểm tra đã có lời mời pending cùng role chưa
        $existingInvitation = IdeaInvitation::where('idea_id', $idea->id)
            ->where('email', $email)
            ->where('role', $role)
            ->where('status', 'pending')
            ->first();

        if ($existingInvitation && $existingInvitation->isValid()) {
            return redirect()->route('my-ideas.show', $idea->id)
                ->with('error', 'Đã có lời mời đang chờ cho email này (' . ($role === 'mentor' ? 'Mentor' : 'Thành viên') . ').');
        }

        // Tạo invitation mới
        $invitation = IdeaInvitation::create([
            'idea_id' => $idea->id,
            'invited_by' => Auth::id(),
            'email' => $email,
            'role' => $role,
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        // Gửi email mời
        try {
            Mail::to($email)->send(new IdeaInvitationMail($invitation));
        } catch (\Exception $e) {
            \Log::error('Failed to send invitation email: ' . $e->getMessage());
        }

        return redirect()->route('my-ideas.show', $idea->id)
            ->with('status', 'Đã gửi lời mời ' . ($role === 'mentor' ? 'Mentor' : 'Thành viên') . ' đến ' . $email);
    }
}

