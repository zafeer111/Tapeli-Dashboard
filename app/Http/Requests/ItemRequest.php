<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ItemStatus;

class ItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'availability' => 'nullable|array',
            'availability.*' => 'integer|min:0',
            'status' => 'nullable|in:' . implode(',', array_column(ItemStatus::cases(), 'value')),
        ];
    }
}