<?php

namespace App\Http\Controllers\Api;

use App\Enums\Permission;
use App\Http\Requests\RentalRequest;
use App\Http\Resources\RentalResource;
use App\Repositories\RentalRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ReferralService;
use App\Models\Rental;
use Exception;

class RentalController extends Controller
{
    protected $rentalRepository;
    protected $referralService;

    public function __construct(RentalRepositoryInterface $rentalRepository, ReferralService $referralService)
    {
        $this->rentalRepository = $rentalRepository;
        $this->referralService = $referralService;
    }

    public function index()
    {
        $rentals = $this->rentalRepository->getAll();
        return RentalResource::collection($rentals);
    }

    public function userRentals()
    {
        $user = auth()->user();
        $rentals = Rental::where('user_id', $user->id)->with('tournament')->orderBy('created_at', 'desc')->get();
        return RentalResource::collection($rentals);
    }


    public function store(RentalRequest $request)
    {
        try {
            $user = $request->user();
            if ($user && $user->hasRole(['user', 'super_admin'])) {
                $data = $request->validated();
                $data['user_id'] = $user->id;

                // Apply discount if eligible
                // $this->referralService->applyDiscount($user->id, $data);

                $rental = $this->rentalRepository->create($data);
                return new RentalResource($rental);
            }
            throw new Exception('Unauthorized');
        } catch (Exception $e) {
            throw new Exception('Rental creation failed: ' . $e->getMessage(), 403);
        }
    }

    public function update(RentalRequest $request, $id)
    {
        try {
            $user = $request->user();
            if ($user && $user->hasRole(['user', 'super_admin'])) {
                $data = $request->validated();
                if (!isset($data['user_id'])) {
                    $data['user_id'] = $user->id;
                }
                $rental = $this->rentalRepository->update($id, $data);
                return new RentalResource($rental);
            }
            throw new Exception('Unauthorized');
        } catch (Exception $e) {
            throw new Exception('Rental update failed: ' . $e->getMessage(), 403);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            if ($user && $user->hasRole('super_admin')) {
                $this->rentalRepository->delete($id);
                return response()->json(['message' => 'Rental deleted successfully'], 200);
            }
            throw new Exception('Unauthorized');
        } catch (Exception $e) {
            throw new Exception('Rental deletion failed: ' . $e->getMessage(), 403);
        }
    }
}
