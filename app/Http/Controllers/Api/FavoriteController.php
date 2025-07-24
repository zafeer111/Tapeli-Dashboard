<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Repositories\FavoriteRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class FavoriteController extends Controller
{
    protected $favoriteRepository;

    public function __construct(FavoriteRepositoryInterface $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function index()
    {
        $user = auth()->user();
        $favorites = $this->favoriteRepository->getUserFavorites($user->id);
        return FavoriteResource::collection($favorites);
    }

    public function toggle(FavoriteRequest $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $tournamentId = $request->validated()['tournament_id'];
                $isAdded = $this->favoriteRepository->toggleFavorite($user->id, $tournamentId);
                return response()->json(['message' => $isAdded ? 'Added to favorites' : 'Removed from favorites', 'is_favorite' => $isAdded], 200);
            }
            throw new Exception('Unauthorized');
        } catch (Exception $e) {
            throw new Exception('Favorite toggle failed: ' . $e->getMessage(), 403);
        }
    }
}
