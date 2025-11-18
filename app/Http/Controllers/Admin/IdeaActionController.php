<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\ReviewAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class IdeaActionController extends Controller
{
    /**
     * Update the status of an idea.
     */
    public function updateStatus(Request $request, Idea $idea)
    {
        // The view sends simple statuses, let's map them to the detailed ones if needed
        // For now, we'll just use the value directly as the MVP form suggests.
        $request->validate([
            'status' => 'required|string',
        ]);

        $idea->update(['status' => $request->status]);

        return back()->with('status', 'Trạng thái ý tưởng đã được cập nhật.');
    }

    /**
     * Assign a reviewer to an idea.
     */
    public function assignReviewer(Request $request, Idea $idea)
    {
        $request->validate([
            'reviewer_id' => 'required|exists:users,id',
        ]);

        $reviewer = User::find($request->reviewer_id);

        // Ensure the selected user is a reviewer
        if (!$reviewer || !in_array($reviewer->role, ['staff', 'center', 'board'])) {
            return back()->withErrors(['reviewer_id' => 'Người dùng được chọn không phải là reviewer.']);
        }

        // Create a new review assignment, avoiding duplicates
        ReviewAssignment::updateOrCreate(
            [
                'idea_id' => $idea->id,
                'reviewer_id' => $reviewer->id,
            ],
            [
                'status' => 'pending', // Set status to pending
            ]
        );

        return back()->with('status', 'Reviewer đã được gán cho ý tưởng.');
    }
}

