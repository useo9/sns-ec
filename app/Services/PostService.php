<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function getPaginatedPosts(int $authUserId, int $perPage = 20): LengthAwarePaginator
    {
        return Post::with([
            'user',
            'product',
            'tags',
            'likes' => fn($q) => $q->where('user_id', $authUserId),
            'comments.user',
        ])
        ->withCount('likes')
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

    public function updatePost(Post $post, array $data, ?UploadedFile $image): void
    {
        if ($image) {
            if ($post->image_path && ! str_starts_with($post->image_path, 'http')) {
                Storage::disk('public')->delete($post->image_path);
            }
            $post->image_path = $image->store('posts', 'public');
        }

        $post->product_id = $data['product_id'] ?? null;
        $post->body       = $data['body'];
        $post->save();

        $tagIds = collect(explode(',', $data['tags'] ?? ''))
            ->map(fn($name) => trim($name))
            ->filter()
            ->map(fn($name) => Tag::firstOrCreate(['name' => $name])->id);

        $post->tags()->sync($tagIds);
    }

    public function deletePost(Post $post): void
    {
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();
    }
}
