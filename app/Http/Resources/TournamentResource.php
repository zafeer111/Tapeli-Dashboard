<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'sport_id' => $this->sport_id,
            'sport' => $this->whenLoaded('sport', fn() => [
                'id' => $this->sport->id,
                'name' => $this->sport->name,
            ]),
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'status' => $this->status->value,
        ];
    }
}
