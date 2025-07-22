<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RentalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tournament_id' => $this->tournament_id,
            'tournament_name' => $this->whenLoaded('tournament', fn() => $this->tournament->name),
            'team_name' => $this->team_name,
            'coach_name' => $this->coach_name,
            'field_number' => $this->field_number,
            'items' => $this->items,
            'bundles' => $this->bundles,
            'rental_date' => $this->rental_date,
            'status' => $this->status->value,
            'delivery_assigned_to' => $this->delivery_assigned_to,
            'photo_url' => $this->photo_url,
            'payment_status' => $this->payment_status,
        ];
    }
}
