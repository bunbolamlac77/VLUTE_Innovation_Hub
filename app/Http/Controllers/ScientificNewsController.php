<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScientificNew;

class ScientificNewsController extends Controller
{
    /**
     * Danh sách bản tin với bộ lọc & phân trang
     */
    public function index(Request $request)
    {
        $query = ScientificNew::query();

        // Tìm kiếm theo từ khóa trong tiêu đề, mô tả, nội dung
        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục (category là chuỗi tự do)
        if ($category = $request->string('category')->toString()) {
            $query->where('category', $category);
        }

        $news = $query->orderBy('published_date', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(12)
                      ->withQueryString();

        // Lấy danh sách category duy nhất để đổ dropdown
        $categories = ScientificNew::query()
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('scientific-news.index', compact('news', 'categories'));
    }

    /**
     * Trang chi tiết bản tin
     */
    public function show(ScientificNew $news)
    {
        return view('scientific-news.show', compact('news'));
    }
}
