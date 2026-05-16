<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::with('user')->withCount(['likes', 'comments'])->latest();

        if ($request->filled('q')) {
            $query->where('body', 'like', "%{$request->q}%");
        }

        if ($request->filter === 'hidden') {
            $query->where('is_hidden', true);
        } elseif ($request->filter === 'visible') {
            $query->where('is_hidden', false);
        }

        $posts = $query->paginate(20)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        $post->load(['user', 'product', 'tags', 'comments.user', 'likes.user']);

        return view('admin.posts.show', compact('post'));
    }

    public function hide(Post $post): RedirectResponse
    {
        $post->update(['is_hidden' => !$post->is_hidden]);

        $msg = $post->is_hidden ? '投稿を非公開にしました。' : '投稿を公開に戻しました。';

        return back()->with('success', $msg);
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', '投稿を削除しました。');
    }
}
