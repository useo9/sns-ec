<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'product_id' => null,
            'body'       => fake('ja_JP')->realText(fake()->numberBetween(30, 150)),
            'image_path' => null,
        ];
    }
}
