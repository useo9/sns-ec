<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    public function store(Post $post): JsonResponse
    {
        $post->likes()->firstOrCreate(['user_id' => auth()->id()]);

        return response()->json([
            'liked' => true,
            'count' => $post->likes()->count(),
        ]);
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->likes()->where('user_id', auth()->id())->delete();

        return response()->json([
            'liked' => false,
            'count' => $post->likes()->count(),
        ]);
    }
}
