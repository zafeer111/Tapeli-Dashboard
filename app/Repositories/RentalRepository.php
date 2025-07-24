<?php


namespace App\Repositories;

use App\Models\Rental;
use Illuminate\Support\Facades\DB;

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
            // Comment: Checkout integration to be added later (Stripe, Apple Pay, Google Pay)
            /*
            // Checkout logic here
            $paymentMethod = $data['payment_method'] ?? 'stripe';
            if (!in_array($paymentMethod, ['stripe', 'apple_pay', 'google_pay'])) {
                throw new \Exception('Invalid payment method');
            }
            // Integrate with Stripe/Apple Pay/Google Pay API to process payment
            $data['payment_status'] = 'completed'; // Update after successful payment
            */

            return Rental::create([
                'user_id' => $data['user_id'],
                'tournament_id' => $data['tournament_id'],
                'team_name' => $data['team_name'],
                'coach_name' => $data['coach_name'],
                'field_number' => $data['field_number'],
                'items' => $data['items'] ?? null,
                'bundles' => $data['bundles'] ?? null,
                'instructions' => $data['instructions'] ?? null,
                'drop_off_time' => $data['drop_off_time'] ?? null,
                'promo_code' => $data['promo_code'] ?? null,
                'insurance_option' => $data['insurance_option'] ?? null,
                'damage_waiver' => $data['damage_waiver'] ?? null,
                'rental_date' => $data['rental_date'],
                'delivery_assigned_to' => $data['delivery_assigned_to'] ?? null,
                'payment_method' => $data['payment_method'] ?? null,
                'payment_status' => $data['payment_status'] ?? 'pending',
                'total_amount' => $data['total_amount'] ?? null,
                'status' => $data['status'] ?? 'pending',
            ]);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $rental = Rental::findOrFail($id);
            $rental->update([
                'user_id' => $data['user_id'] ?? $rental->user_id,
                'tournament_id' => $data['tournament_id'] ?? $rental->tournament_id,
                'team_name' => $data['team_name'] ?? $rental->team_name,
                'coach_name' => $data['coach_name'] ?? $rental->coach_name,
                'field_number' => $data['field_number'] ?? $rental->field_number,
                'items' => $data['items'] ?? $rental->items,
                'bundles' => $data['bundles'] ?? $rental->bundles,
                'instructions' => $data['instructions'] ?? $rental->instructions,
                'drop_off_time' => $data['drop_off_time'] ?? $rental->drop_off_time,
                'promo_code' => $data['promo_code'] ?? $rental->promo_code,
                'insurance_option' => $data['insurance_option'] ?? $rental->insurance_option,
                'damage_waiver' => $data['damage_waiver'] ?? $rental->damage_waiver,
                'rental_date' => $data['rental_date'] ?? $rental->rental_date,
                'delivery_assigned_to' => $data['delivery_assigned_to'] ?? $rental->delivery_assigned_to,
                'payment_method' => $data['payment_method'] ?? $rental->payment_method,
                'payment_status' => $data['payment_status'] ?? $rental->payment_status,
                'total_amount' => $data['total_amount'] ?? $rental->total_amount,
                'status' => $data['status'] ?? $rental->status,
            ]);
            return $rental;
        });
    }

    public function delete($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->delete();
    }
}
