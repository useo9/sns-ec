<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $brands = ['LEVI\'S', 'Wrangler', 'Lee', 'Carhartt', 'Dickies', 'Champion', 'Ralph Lauren', 'Tommy Hilfiger'];
        $categories = ['トップス', 'ボトムス', 'アウター', 'シューズ', 'アクセサリー'];
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];

        return [
            'user_id'     => User::factory(),
            'title'       => fake('ja_JP')->realText(30),
            'description' => fake('ja_JP')->realText(100),
            'price'       => fake()->numberBetween(500, 30000),
            'brand'       => fake()->randomElement($brands),
            'size'        => fake()->randomElement($sizes),
            'category'    => fake()->randomElement($categories),
            'condition'   => fake()->numberBetween(1, 6),
            'status'      => 1, // 出品中
            'image_path'  => null,
        ];
    }
}
