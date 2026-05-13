<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Post $post, Request $request): JsonResponse
    {
        $validated = $request->validate(['body' => 'required|string|max:500']);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'body'    => $validated['body'],
        ]);

        $comment->load('user');

        return response()->json([
            'id'         => $comment->id,
            'body'       => $comment->body,
            'user_id'    => $comment->user_id,
            'user_name'  => $comment->user->name,
            'created_at' => $comment->created_at->diffForHumans(),
        ]);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        abort_if($comment->user_id !== auth()->id(), 403);

        $comment->delete();

        return response()->json(['deleted' => true]);
    }
}
