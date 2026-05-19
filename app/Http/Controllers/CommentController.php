<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Startup;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, $startupId)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
            'parent_id' => 'nullable|string'
        ]);

        $startup = Startup::findOrFail($startupId);

        $comment = Comment::create([
            'startup_id' => $startup->id,
            'user_id' => Auth::id(),
            'body' => $request->body,
            'parent_id' => $request->parent_id
        ]);

        // Notifications
        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            if ($parentComment && $parentComment->user_id !== Auth::id() && $parentComment->user) {
                $parentComment->user->notify(new \App\Notifications\NewCommentNotification($startup, Auth::user()->name, $comment->body, true));
            }
        } else {
            // Root comment -> notify founder
            if ($startup->founder && $startup->founder_id !== Auth::id()) {
                $startup->founder->notify(new \App\Notifications\NewCommentNotification($startup, Auth::user()->name, $comment->body, false));
            }
        }

        return back()->with('success', 'Comment posted successfully!');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete replies manually
        Comment::where('parent_id', $comment->id)->delete();
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
