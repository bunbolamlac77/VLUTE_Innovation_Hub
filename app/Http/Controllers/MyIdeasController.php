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
            ->with('status', 'Ý tưởng đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết ý tưởng
     */
    public function show($id)
    {
        $idea = Idea::with([
            'owner',
            'faculty',
            'category',
            'members.user',
            'invitations',
            'reviewAssignments' => function ($query) {
                $query->with(['reviewer', 'review.changeRequests']);
            },
            'changeRequests',
            'attachments.uploader'
        ])->findOrFail($id);

        // Kiểm tra quyền xem
        $this->authorize('view', $idea);

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
    public function edit($id)
    {
        $idea = Idea::findOrFail($id);

        // Kiểm tra quyền cập nhật
        $this->authorize('update', $idea);

        $faculties = Faculty::orderBy('sort_order')->orderBy('name')->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('my-ideas.edit', compact('idea', 'faculties', 'categories'));
    }

    /**
     * Cập nhật ý tưởng
     */
    public function update(Request $request, $id)
    {
        $idea = Idea::findOrFail($id);

        // Kiểm tra quyền cập nhật
        $this->authorize('update', $idea);

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
    public function destroy($id)
    {
        $idea = Idea::findOrFail($id);

        // Kiểm tra quyền xóa
        $this->authorize('delete', $idea);

        $idea->delete();

        return redirect()->route('my-ideas.index')
            ->with('status', 'Ý tưởng đã được xóa thành công!');
    }

    /**
     * Nộp ý tưởng (chuyển từ Draft sang Submitted)
     */
    public function submit($id)
    {
        $idea = Idea::findOrFail($id);

        // Kiểm tra quyền nộp
        $this->authorize('submit', $idea);

        // Nếu đang ở trạng thái needs_change, cần xác định cấp nào yêu cầu chỉnh sửa
        if ($idea->needsChange()) {
            // Nếu là needs_change_gv, nộp lại lên GV
            if ($idea->status === 'needs_change_gv') {
                $idea->status = 'submitted_gv';
            } elseif ($idea->status === 'needs_change_center') {
                $idea->status = 'submitted_center';
            } elseif ($idea->status === 'needs_change_board') {
                $idea->status = 'submitted_board';
            }
        } else {
            // Nếu đang là Draft, nộp lên cấp đầu tiên (GV)
            $idea->status = 'submitted_gv';
        }

        $idea->save();

        return redirect()->route('my-ideas.show', $idea->id)
            ->with('status', 'Ý tưởng đã được nộp thành công!');
    }

    /**
     * Mời thành viên vào ý tưởng
     */
    public function invite(Request $request, $id)
    {
        $idea = Idea::findOrFail($id);

        // Kiểm tra quyền mời
        $this->authorize('invite', $idea);

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = $validated['email'];

        // Kiểm tra user có tồn tại không
        $invitedUser = User::where('email', $email)->first();

        // Kiểm tra user đã là member chưa
        if (
            $invitedUser && $idea->members->contains(function ($member) use ($invitedUser) {
                return $member->user_id === $invitedUser->id;
            })
        ) {
            return redirect()->route('my-ideas.show', $idea->id)
                ->with('error', 'Người dùng này đã là thành viên của ý tưởng.');
        }

        // Kiểm tra đã có lời mời pending chưa
        $existingInvitation = IdeaInvitation::where('idea_id', $idea->id)
            ->where('email', $email)
            ->where('status', 'pending')
            ->first();

        if ($existingInvitation && $existingInvitation->isValid()) {
            return redirect()->route('my-ideas.show', $idea->id)
                ->with('error', 'Đã có lời mời đang chờ phản hồi cho email này.');
        }

        // Tạo invitation mới
        $invitation = IdeaInvitation::create([
            'idea_id' => $idea->id,
            'invited_by' => Auth::id(),
            'email' => $email,
            'status' => 'pending',
            'expires_at' => now()->addDays(7), // Hết hạn sau 7 ngày
        ]);

        // Gửi email mời
        try {
            Mail::to($email)->send(new IdeaInvitationMail($invitation));
        } catch (\Exception $e) {
            // Log lỗi nhưng vẫn lưu invitation
            \Log::error('Failed to send invitation email: ' . $e->getMessage());
        }

        return redirect()->route('my-ideas.show', $idea->id)
            ->with('status', 'Lời mời đã được gửi đến ' . $email);
    }
}

