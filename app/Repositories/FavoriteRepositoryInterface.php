<?php

namespace App\Repositories;

interface FavoriteRepositoryInterface
{
    public function toggleFavorite($userId, $tournamentId);
    public function getUserFavorites($userId);
}
