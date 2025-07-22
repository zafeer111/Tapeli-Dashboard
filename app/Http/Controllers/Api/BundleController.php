<?php

namespace App\Http\Controllers\Api;

use App\Enums\Permission;
use App\Http\Requests\BundleRequest;
use App\Http\Resources\BundleResource;
use App\Repositories\BundleRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class BundleController extends Controller
{
    protected $bundleRepository;

    public function __construct(BundleRepositoryInterface $bundleRepository)
    {
        $this->bundleRepository = $bundleRepository;
    }

    public function index()
    {
        $bundles = $this->bundleRepository->getAllAvailable();
        return BundleResource::collection($bundles);
    }

    public function store(BundleRequest $request)
    {
        try {
            $user = $request->user();
            if ($user && ($user->hasPermissionTo(Permission::SUPER_ADMIN->value) ||
                $user->hasPermissionTo(Permission::MANAGER->value))) {
                $bundle = $this->bundleRepository->create(
                    $request->only(['name', 'description', 'price', 'status']),
                    $request->input('items', [])
                );
                return new BundleResource($bundle->load('items'));
            }
            throw new Exception('Unauthorized');
        } catch (Exception $e) {
            throw new Exception('Bundle creation failed: ' . $e->getMessage(), 403);
        }
    }

    public function update(BundleRequest $request, $id)
    {
        try {
            $user = $request->user();
            if ($user && ($user->hasPermissionTo(Permission::SUPER_ADMIN->value) ||
                $user->hasPermissionTo(Permission::MANAGER->value))) {
                $bundle = $this->bundleRepository->update(
                    $id,
                    $request->only(['name', 'description', 'price', 'status']),
                    $request->input('items', [])
                );
                return new BundleResource($bundle->load('items'));
            }
            throw new Exception('Unauthorized');
        } catch (Exception $e) {
            throw new Exception('Bundle update failed: ' . $e->getMessage(), 403);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            if ($user && ($user->hasPermissionTo(Permission::SUPER_ADMIN->value) ||
                $user->hasPermissionTo(Permission::MANAGER->value))) {
                $this->bundleRepository->delete($id);
                return response()->json(['message' => 'Bundle deleted successfully'], 200);
            }
            throw new Exception('Unauthorized');
        } catch (Exception $e) {
            throw new Exception('Bundle deletion failed: ' . $e->getMessage(), 403);
        }
    }
}
