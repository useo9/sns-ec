<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'             => ['required', 'string', 'max:100'],
            'description'       => ['nullable', 'string', 'max:2000'],
            'price'             => ['required', 'integer', 'min:1', 'max:9999999'],
            'brand'             => ['nullable', 'string', 'max:50'],
            'size'              => ['nullable', 'string', 'max:20'],
            'category'          => ['nullable', 'string', 'max:50'],
            'condition'         => ['required', 'integer', 'between:1,6'],
            'status'            => ['required', 'integer', 'in:0,1'],
            'tags'              => ['nullable', 'string', 'max:255'],
            'images'            => ['nullable', 'array', 'max:5'],
            'images.*'          => ['image', 'max:5120'],
            'delete_image_ids'  => ['nullable', 'array'],
            'delete_image_ids.*'=> ['integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title'       => '商品名',
            'description' => '商品説明',
            'price'       => '価格',
            'brand'       => 'ブランド',
            'size'        => 'サイズ',
            'category'    => 'カテゴリ',
            'condition'   => '商品の状態',
            'status'      => '出品状態',
            'images.*'    => '商品画像',
        ];
    }
}
