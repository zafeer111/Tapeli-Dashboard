<?php

namespace App\Repositories;

use App\Models\Tournament;
use Illuminate\Support\Facades\DB;
use App\Enums\TournamentStatus;

class TournamentRepository implements TournamentRepositoryInterface
{
    public function getAllActive()
    {
        return Tournament::where('status', TournamentStatus::ACTIVE->value)
            ->with('sport')
            ->get();
    }

    public function find($id)
    {
        return Tournament::with('sport')->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Tournament::create([
                'sport_id' => $data['sport_id'],
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'location' => $data['location'],
                'status' => $data['status'] ?? TournamentStatus::ACTIVE->value,
            ]);

            // Optional Airtable sync (commented out)
            /*
            if (config('services.airtable.enabled')) {
                Http::withToken(config('services.airtable.api_key'))
                    ->post('https://api.airtable.com/v0/' . config('services.airtable.base_id') . '/Tournaments', [
                        'fields' => [
                            'Name' => $tournament->name,
                            'Start Date' => $tournament->start_date,
                            'End Date' => $tournament->end_date,
                            'Location' => $tournament->location,
                            'Status' => $tournament->status,
                            'Dates' => json_encode($tournament->dates),
                        ],
                    ]);
            }
            */
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $tournament = Tournament::findOrFail($id);
            $tournament->update([
                'sport_id' => $data['sport_id'] ?? $tournament->sport_id,
                'name' => $data['name'] ?? $tournament->name,
                'start_date' => $data['start_date'] ?? $tournament->start_date,
                'end_date' => $data['end_date'] ?? $tournament->end_date,
                'location' => $data['location'] ?? $tournament->location,
                'status' => $data['status'] ?? $tournament->status,
            ]);

            // Optional Airtable sync (commented out)
            /*
            if (config('services.airtable.enabled')) {
                Http::withToken(config('services.airtable.api_key'))
                    ->patch('https://api.airtable.com/v0/' . config('services.airtable.base_id') . '/Tournaments/' . $tournament->airtable_id, [
                        'fields' => [
                            'Name' => $tournament->name,
                            'Start Date' => $tournament->start_date,
                            'End Date' => $tournament->end_date,
                            'Location' => $tournament->location,
                            'Status' => $tournament->status,
                            'Dates' => json_encode($tournament->dates),
                        ],
                    ]);
            }
            */

            return $tournament;
        });
    }


    public function delete($id)
    {
        $tournament = Tournament::findOrFail($id);
        $tournament->delete();

        // Optional Airtable sync (commented out)
        /*
        if (config('services.airtable.enabled')) {
            Http::withToken(config('services.airtable.api_key'))
                ->delete('https://api.airtable.com/v0/' . config('services.airtable.base_id') . '/Tournaments/' . $tournament->airtable_id);
        }
        */
    }
}
