<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::latest()->paginate(20);
        return view('admin.competitions.index', compact('competitions'));
    }

    public function create()
    {
        return view('admin.competitions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,open,judging,closed,archived',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . substr(Str::uuid()->toString(), 0, 8);
        $data['created_by'] = $request->user()->id;

        Competition::create($data);

        return redirect()->route('admin.competitions.index')->with('status', 'Tạo cuộc thi thành công');
    }

    public function edit(Competition $competition)
    {
        return view('admin.competitions.edit', compact('competition'));
    }

    public function update(Request $request, Competition $competition)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,open,judging,closed,archived',
        ]);

        // Không thay đổi slug sau khi tạo để tránh gãy liên kết công khai
        $competition->update($data);

        return redirect()->route('admin.competitions.index')->with('status', 'Cập nhật cuộc thi thành công');
    }

    public function destroy(Competition $competition)
    {
        $competition->delete(); // Soft delete
        return redirect()->route('admin.competitions.index')->with('status', 'Đã xóa cuộc thi');
    }
}

