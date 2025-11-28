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
            'published_date' => 'nullable|date',
            'category' => 'nullable|string|max:255',
        ]);

        ScientificNew::create($data);

        return redirect()->route('admin.news.index')->with('status', 'Tạo bản tin thành công');
    }

    // TODO: edit, update, destroy
}

