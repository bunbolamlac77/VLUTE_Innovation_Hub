<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\Organization;
use Illuminate\Http\Request;

class AdminChallengeController extends Controller
{
    public function index()
    {
        $challenges = Challenge::with('organization')->latest()->paginate(20);
        return view('admin.challenges.index', compact('challenges'));
    }

    public function create()
    {
        $organizations = Organization::orderBy('name')->get();
        return view('admin.challenges.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'organization_id' => 'required|exists:organizations,id',
            'deadline' => 'nullable|date',
            'reward' => 'nullable|string|max:255',
            'status' => 'required|in:draft,open,closed',
        ]);

        Challenge::create($data);

        return redirect()->route('admin.challenges.index')->with('status', 'Tạo challenge thành công');
    }

    public function edit(Challenge $challenge)
    {
        $organizations = Organization::orderBy('name')->get();
        return view('admin.challenges.edit', compact('challenge','organizations'));
    }

    public function update(Request $request, Challenge $challenge)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'organization_id' => 'required|exists:organizations,id',
            'deadline' => 'nullable|date',
            'reward' => 'nullable|string|max:255',
            'status' => 'required|in:draft,open,closed',
        ]);

        $challenge->update($data);

        return redirect()->route('admin.challenges.index')->with('status', 'Cập nhật challenge thành công');
    }

    public function destroy(Challenge $challenge)
    {
        $challenge->delete();
        return redirect()->route('admin.challenges.index')->with('status', 'Đã xóa challenge');
    }
}

