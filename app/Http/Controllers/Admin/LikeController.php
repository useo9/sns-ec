<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LikeController extends Controller
{
    public function index(Request $request): View
    {
        $likes = Like::with(['user', 'post.user'])->latest()->paginate(20)->withQueryString();

        return view('admin.likes.index', compact('likes'));
    }
}
