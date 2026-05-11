<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
    public function getPaginatedPosts(int $perPage = 20): LengthAwarePaginator
    {
        return Post::with(['user', 'product', 'tags'])
            ->latest()
            ->paginate($perPage);
    }

    public function createPost(User $user, array $data, ?UploadedFile $image): Post
    {
        $imagePath = $image?->store('posts', 'public');

        $post = Post::create([
            'user_id'    => $user->id,
            'product_id' => $data['product_id'] ?? null,
            'body'       => $data['body'],
            'image_path' => $imagePath,
        ]);

        if (! empty($data['tags'])) {
            $tagIds = collect(explode(',', $data['tags']))
                ->map(fn($name) => trim($name))
                ->filter()
                ->map(fn($name) => Tag::firstOrCreate(['name' => $name])->id);

            $post->tags()->sync($tagIds);
        }

        return $post;
    }
}
