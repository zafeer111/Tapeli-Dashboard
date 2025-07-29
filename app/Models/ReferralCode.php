<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function referredUsers()
    {
        return $this->hasMany(ReferralTracking::class, 'referral_code', 'code');
    }
}
