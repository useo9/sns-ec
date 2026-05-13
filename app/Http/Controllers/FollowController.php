<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class FollowController extends Controller
{
    public function store(User $user): RedirectResponse
    {
        abort_if(auth()->id() === $user->id, 403);

        auth()->user()->following()->syncWithoutDetaching($user->id);

        return back();
    }

    public function destroy(User $user): RedirectResponse
    {
        auth()->user()->following()->detach($user->id);

        return back();
    }
}
