<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\IdeaComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Notifications\NewIdeaComment;

class IdeaCommentController extends Controller
{
    public function store(Request $request, Idea $idea): RedirectResponse
    {
        // User must be in the team (owner/member/mentor)
        $user = Auth::user();
        $inTeam = $user->id === $idea->owner_id || $idea->members()->where('user_id', $user->id)->exists();
        if (!$inTeam) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:5000'],
        ]);

        $comment = IdeaComment::create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => $validated['body'],
            'visibility' => 'team_only',
        ]);

        // Notify owner and team members (except the author)
        try {
            $idea->loadMissing(['owner', 'members.user']);
            $notifiedIds = [$user->id];
            if ($idea->owner && !\in_array($idea->owner->id, $notifiedIds, true)) {
                $idea->owner->notify(new NewIdeaComment($idea, $comment));
                $notifiedIds[] = $idea->owner->id;
            }
            foreach ($idea->members as $m) {
                if ($m->user && !\in_array($m->user->id, $notifiedIds, true)) {
                    $m->user->notify(new NewIdeaComment($idea, $comment));
                    $notifiedIds[] = $m->user->id;
                }
            }
        } catch (\Throwable $e) {
            // ignore notify errors
        }

        return redirect()->route('my-ideas.show', $idea->id)
            ->with('status', 'Đã gửi góp ý nội bộ.');
    }

    public function destroy(Idea $idea, IdeaComment $comment): RedirectResponse
    {
        $user = Auth::user();
        $inTeam = $user->id === $idea->owner_id || $idea->members()->where('user_id', $user->id)->exists();
        if (!$inTeam) {
            abort(403);
        }

        // Only comment author or idea owner can delete
        if ($comment->user_id !== $user->id && $user->id !== $idea->owner_id) {
            abort(403);
        }

        if ($comment->idea_id !== $idea->id) {
            abort(404);
        }

        $comment->delete();

        return redirect()->route('my-ideas.show', $idea->id)
            ->with('status', 'Đã xóa góp ý.');
    }
}

