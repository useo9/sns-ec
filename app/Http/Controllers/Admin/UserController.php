<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::withCount(['orders', 'products', 'posts'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }
}
