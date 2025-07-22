<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ItemStatus;

class BundleRequest extends FormRequest
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
            // 'items' => 'required|array|min:1',
            // 'items.*.item_id' => 'required|exists:items,id',
            // 'items.*.quantity' => 'required|integer|min:1',


            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',

            'status' => 'nullable|in:' . implode(',', array_column(ItemStatus::cases(), 'value')),
        ];
    }
}
