<?php

namespace App\Services;

use App\Models\ReferralCode;
use App\Models\ReferralTracking;
use App\Models\UserCredit;
use Illuminate\Support\Str;

class ReferralService
{
    public function generateCode($userId)
    {
        do {
            $code = 'GDV' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (ReferralCode::where('code', $code)->exists());

        ReferralCode::create(['user_id' => $userId, 'code' => $code]);
        return $code;
    }

    public function trackReferral($referralCode, $newUserId)
    {
        $referral = ReferralCode::where('code', $referralCode)->first();
        if ($referral) {
            $referrerId = $referral->user_id;
            if (!ReferralTracking::where('referrer_id', $referrerId)->where('referred_user_id', $newUserId)->exists()) {
                $tracking = ReferralTracking::create([
                    'referrer_id' => $referrerId,
                    'referred_user_id' => $newUserId,
                    'referral_code' => $referralCode,
                ]);
                $this->awardCredit($referrerId);
                $tracking->update(['credit_awarded' => true]); // Mark credit awarded
                return $tracking;
            }
        }
        return null;
    }

    public function awardCredit($referrerId)
    {
        $credit = UserCredit::firstOrCreate(
            ['user_id' => $referrerId],
            ['amount' => 0.00, 'type' => 'referral']
        );
        $credit->increment('amount', 5.00);
        return $credit;
    }

    public function applyDiscount($userId, $rentalData)
    {
        $tracking = ReferralTracking::where('referred_user_id', $userId)->first();
        if ($tracking && $rentalData['payment_status'] === 'paid' && !$this->hasPreviousPaidRental($userId)) {
            // Apply $5 discount (deduct from total_amount)
            $rentalData['total_amount'] = max(0, $rentalData['total_amount'] - 5.00);
            return true;
        }
        return false;
    }

    protected function hasPreviousPaidRental($userId)
    {
        return \App\Models\Rental::where('user_id', $userId)
            ->where('payment_status', 'paid')
            ->where('id', '!=', request()->route('id') ?? null)
            ->exists();
    }
}
