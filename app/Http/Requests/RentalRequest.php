<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\RentalStatus;

class RentalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tournament_id' => 'required|exists:tournaments,id',
            'team_name' => 'required|string|max:255',
            'coach_name' => 'required|string|max:255',
            'field_number' => 'required|string|max:50',
            'items' => 'nullable|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'bundles' => 'nullable|array',
            'bundles.*' => 'exists:bundles,id',
            'rental_date' => 'required|date',
            'status' => 'nullable|in:' . implode(',', array_column(RentalStatus::cases(), 'value')),
            'delivery_assigned_to' => 'nullable|string',
            'photo_url' => 'nullable|string|url',
            'payment_status' => 'nullable|in:pending,completed',
        ];
    }
}
