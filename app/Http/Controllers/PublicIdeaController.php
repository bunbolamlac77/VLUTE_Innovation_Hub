<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\IdeaComment;
use App\Models\Faculty;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicIdeaController extends Controller
{
    /**
     * Hiển thị danh sách ý tưởng công khai (Ngân hàng Ý tưởng)
     */
    public function index(Request $request)
    {
        // Lấy các ý tưởng đã được duyệt cuối và công khai
        $query = Idea::publicApproved()
            ->with(['owner', 'faculty', 'category', 'members.user', 'likes'])
            ->withCount('likes')
            ->latest();

        // Lọc theo khoa
        if ($request->filled('faculty')) {
            $query->where('faculty_id', $request->faculty);
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Tìm kiếm theo từ khóa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sắp xếp
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'most_liked':
                $query->orderBy('like_count', 'desc');
                break;
            case 'most_viewed':
                // Nếu có view_count field thì dùng, nếu không thì dùng created_at
                $query->orderBy('created_at', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $ideas = $query->paginate(12)->withQueryString();

        // Lấy danh sách khoa và danh mục để hiển thị trong filter
        $faculties = Faculty::orderBy('sort_order')->orderBy('name')->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('ideas.index', compact('ideas', 'faculties', 'categories'));
    }

    /**
     * Hiển thị chi tiết một ý tưởng công khai
     */
    public function show($slug)
    {
        $idea = Idea::publicApproved()
            ->where('slug', $slug)
            ->with(['owner', 'faculty', 'category', 'members.user', 'likes', 'comments.user'])
            ->withCount('likes')
            ->firstOrFail();

        return view('ideas.show', compact('idea'));
    }

    /**
     * Like/Unlike ý tưởng (cần đăng nhập)
     */
    public function like($id)
    {
        $idea = Idea::publicApproved()->findOrFail($id);
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thực hiện chức năng này'
            ], 401);
        }

        // Kiểm tra user đã like chưa
        $isLiked = $idea->isLikedBy($user);

        if ($isLiked) {
            // Nếu đã like thì unlike
            $idea->likes()->detach($user->id);
            $liked = false;
        } else {
            // Nếu chưa like thì like
            $idea->likes()->attach($user->id);
            $liked = true;
        }

        // Cập nhật like_count trong bảng ideas để đồng bộ
        $idea->like_count = $idea->likes()->count();
        $idea->save();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $idea->like_count
        ]);
    }

    /**
     * Lưu bình luận công khai cho ý tưởng (cần đăng nhập)
     */
    public function storeComment(Request $request, $id)
    {
        $idea = Idea::publicApproved()->findOrFail($id);
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để bình luận.');
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:2', 'max:5000'],
        ]);

        $comment = IdeaComment::create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => $validated['content'],
            'visibility' => 'public',
        ]);

        return redirect()->route('ideas.show', $idea->slug)
            ->with('success', 'Đã gửi bình luận thành công!');
    }
}

