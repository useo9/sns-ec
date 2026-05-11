<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index(): View
    {
        $posts = $this->postService->getPaginatedPosts();

        return view('posts.index', compact('posts'));
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
            $request->file('image'),
        );

        return redirect()->route('posts.index')->with('success', '投稿しました。');
    }
}
