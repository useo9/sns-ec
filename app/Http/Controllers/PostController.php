<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index(): View
    {
        $posts = $this->postService->getPaginatedPosts(auth()->id());

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        $post->load([
            'user',
            'product',
            'postImages',
            'tags',
            'likes' => fn($q) => $q->where('user_id', auth()->id()),
            'comments' => fn($q) => $q->with('user')->oldest(),
        ]);
        $post->loadCount('likes');

        return view('posts.show', compact('post'));
    }

    public function create(): View
    {
        $products = auth()->user()
            ->products()
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->get(['id', 'title', 'image_path']);

        return view('posts.create', compact('products'));
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $this->postService->createPost(
            auth()->user(),
            $request->validated(),
            $request->file('images', []),
        );

        return redirect()->route('posts.index')->with('success', '投稿しました。');
    }

    public function edit(Post $post): View
    {
        abort_if($post->user_id !== auth()->id(), 403);

        $products = auth()->user()
            ->products()
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->get(['id', 'title', 'image_path']);

        return view('posts.edit', compact('post', 'products'));
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        abort_if($post->user_id !== auth()->id(), 403);

        $this->postService->updatePost(
            $post,
            $request->validated(),
            $request->file('images', []),
        );

        return redirect()->route('posts.show', $post)->with('success', '投稿を更新しました。');
    }

    public function destroy(Post $post): RedirectResponse
    {
        abort_if($post->user_id !== auth()->id(), 403);

        $this->postService->deletePost($post);

        return redirect()->route('posts.index')->with('success', '投稿を削除しました。');
    }
}
