<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function show(User $user): View
    {
        $posts = $user->posts()
            ->with(['product', 'tags', 'likes', 'comments.user'])
            ->withCount('likes')
            ->latest()
            ->paginate(10);

        $isFollowing = auth()->user()->isFollowing($user);
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('users.show', compact('user', 'posts', 'isFollowing', 'followersCount', 'followingCount'));
    }

    public function mypage(): View
    {
        $user = auth()->user();

        $posts = $user->posts()
            ->with(['product', 'tags', 'likes', 'comments.user'])
            ->withCount('likes')
            ->latest()
            ->paginate(10);

        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();
        $postsCount = $user->posts()->count();

        return view('mypage.index', compact('user', 'posts', 'followersCount', 'followingCount', 'postsCount'));
    }
}
