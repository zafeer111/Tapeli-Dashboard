<?php


namespace App\Repositories;

use App\Models\Favorite;
use Illuminate\Support\Facades\DB;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    public function toggleFavorite($userId, $tournamentId)
    {
        return DB::transaction(function () use ($userId, $tournamentId) {
            $favorite = Favorite::where('user_id', $userId)->where('tournament_id', $tournamentId)->first();
            if ($favorite) {
                $favorite->delete();
                return false;
            } else {
                Favorite::create(['user_id' => $userId, 'tournament_id' => $tournamentId]);
                return true;
            }
        });
    }

    public function getUserFavorites($userId)
    {
        return Favorite::where('user_id', $userId)->with('tournament')->get();
    }
}
