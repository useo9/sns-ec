<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_name' => ['required', 'string', 'max:100'],
            'shipping_postal_code' => ['required', 'string', 'regex:/^\d{3}-?\d{4}$/'],
            'shipping_prefecture' => ['required', 'string'],
            'shipping_address' => ['required', 'string', 'max:200'],
            'shipping_building' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'shipping_name' => 'お名前',
            'shipping_postal_code' => '郵便番号',
            'shipping_prefecture' => '都道府県',
            'shipping_address' => '住所',
            'shipping_building' => '建物名・部屋番号',
        ];
    }
}
