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
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
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
            'image' => '画像',
            'product_id' => '商品',
            'tags' => 'タグ',
        ];
    }
}
