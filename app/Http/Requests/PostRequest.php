<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:1000'],
            'images'   => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,webp', 'max:5120'],
            'product_id' => [
                'nullable',
                Rule::exists('products', 'id')->where('user_id', $this->user()->id),
            ],
            'tags' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function attributes(): array
    {
        return [
            'body' => '本文',
            'images'   => '画像',
            'images.*' => '画像',
            'product_id' => '商品',
            'tags' => 'タグ',
        ];
    }
}
