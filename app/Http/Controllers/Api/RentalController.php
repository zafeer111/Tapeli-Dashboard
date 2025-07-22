<?php

namespace App\Http\Controllers\Api;

use App\Enums\Permission;
use App\Http\Requests\RentalRequest;
use App\Http\Resources\RentalResource;
use App\Repositories\RentalRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class RentalController extends Controller
{
    protected $rentalRepository;

    public function __construct(RentalRepositoryInterface $rentalRepository)
    {
        $this->rentalRepository = $rentalRepository;
    }

    public function index()
    {
        $rentals = $this->rentalRepository->getAll();
        return RentalResource::collection($rentals);
    }

    public function store(RentalRequest $request)
    {
        try {
            $user = $request->user();
            if ($user && $user->hasPermissionTo(Permission::USER->value)) {
                $rental = $this->rentalRepository->create($request->validated());
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
            if ($user && ($user->hasPermissionTo(Permission::SUPER_ADMIN->value) ||
                $user->hasPermissionTo(Permission::MANAGER->value))) {
                $rental = $this->rentalRepository->update($id, $request->validated());
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
            if ($user && ($user->hasPermissionTo(Permission::SUPER_ADMIN->value) ||
                $user->hasPermissionTo(Permission::MANAGER->value))) {
                $this->rentalRepository->delete($id);
                return response()->json(['message' => 'Rental deleted successfully'], 200);
            }
            throw new Exception('Unauthorized');
        } catch (Exception $e) {
            throw new Exception('Rental deletion failed: ' . $e->getMessage(), 403);
        }
    }
}
