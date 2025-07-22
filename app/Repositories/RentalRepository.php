<?php

namespace App\Repositories;

use App\Models\Rental;
use App\Models\Item;
use App\Models\Bundle;
use Illuminate\Support\Facades\DB;
use App\Enums\RentalStatus;

class RentalRepository implements RentalRepositoryInterface
{
    public function getAll()
    {
        return Rental::with('tournament')->get();
    }

    public function find($id)
    {
        return Rental::with('tournament')->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $this->checkAvailability($data['items'] ?? [], $data['bundles'] ?? [], $data['rental_date']);
            return Rental::create([
                'tournament_id' => $data['tournament_id'],
                'team_name' => $data['team_name'],
                'coach_name' => $data['coach_name'],
                'field_number' => $data['field_number'],
                'items' => $data['items'] ?? null,
                'bundles' => $data['bundles'] ?? null,
                'rental_date' => $data['rental_date'],
                'status' => $data['status'] ?? RentalStatus::PENDING->value,
                'delivery_assigned_to' => $data['delivery_assigned_to'] ?? null,
                'photo_url' => $data['photo_url'] ?? null,
                'payment_status' => $data['payment_status'] ?? 'pending',
            ]);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $rental = Rental::findOrFail($id);
            $rental->update([
                'tournament_id' => $data['tournament_id'] ?? $rental->tournament_id,
                'team_name' => $data['team_name'] ?? $rental->team_name,
                'coach_name' => $data['coach_name'] ?? $rental->coach_name,
                'field_number' => $data['field_number'] ?? $rental->field_number,
                'items' => $data['items'] ?? $rental->items,
                'bundles' => $data['bundles'] ?? $rental->bundles,
                'rental_date' => $data['rental_date'] ?? $rental->rental_date,
                'status' => $data['status'] ?? $rental->status,
                'delivery_assigned_to' => $data['delivery_assigned_to'] ?? $rental->delivery_assigned_to,
                'photo_url' => $data['photo_url'] ?? $rental->photo_url,
                'payment_status' => $data['payment_status'] ?? $rental->payment_status,
            ]);
            return $rental;
        });
    }

    public function delete($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->delete();
    }

    public function checkAvailability($items, $bundles, $date)
    {
        // Check items availability
        foreach ($items as $item) {
            $itemModel = Item::findOrFail($item['item_id']);
            if ($itemModel->status !== \App\Enums\ItemStatus::AVAILABLE->value) {
                throw new \Exception("Item {$itemModel->name} is unavailable");
            }
            if ($itemModel->availability && isset($itemModel->availability[$date])) {
                if ($itemModel->availability[$date] < $item['quantity']) {
                    throw new \Exception("Insufficient stock for item {$itemModel->name} on $date");
                }
            } elseif ($itemModel->stock < $item['quantity']) {
                throw new \Exception("Insufficient total stock for item {$itemModel->name}");
            }
        }

        // Check bundles availability (via their items)
        foreach ($bundles as $bundleId) {
            $bundle = Bundle::with('items')->findOrFail($bundleId);
            foreach ($bundle->items as $item) {
                $itemModel = Item::findOrFail($item->id);
                if ($itemModel->status !== \App\Enums\ItemStatus::AVAILABLE->value) {
                    throw new \Exception("Item {$itemModel->name} in bundle {$bundle->name} is unavailable");
                }
                $requiredQuantity = $item->pivot->quantity;
                if ($itemModel->availability && isset($itemModel->availability[$date])) {
                    if ($itemModel->availability[$date] < $requiredQuantity) {
                        throw new \Exception("Insufficient stock for item {$itemModel->name} in bundle {$bundle->name} on $date");
                    }
                } elseif ($itemModel->stock < $requiredQuantity) {
                    throw new \Exception("Insufficient total stock for item {$itemModel->name} in bundle {$bundle->name}");
                }
            }
        }
        return true;
    }
}
