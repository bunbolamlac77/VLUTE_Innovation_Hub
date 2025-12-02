<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScientificNew;
use Illuminate\Http\Request;


class AdminNewsController extends Controller
{
    public function index()
    {
        $news = ScientificNew::latest()->paginate(20);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'author' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'published_date' => 'required|date',
            'category' => 'nullable|string|max:255',
        ]);

        ScientificNew::create($data);

        return redirect()->route('admin.news.index')->with('status', 'Tạo bản tin thành công');
    }

    public function edit(ScientificNew $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, ScientificNew $news)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'author' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'published_date' => 'required|date',
            'category' => 'nullable|string|max:255',
        ]);

        $news->update($data);

        return redirect()->route('admin.news.index')->with('status', 'Cập nhật bản tin thành công');
    }

    public function destroy(ScientificNew $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('status', 'Đã xóa bản tin');
    }
}

