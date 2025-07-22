<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\RentalStatus;

class Rental extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tournament_id',
        'team_name',
        'coach_name',
        'field_number',
        'items',
        'bundles',
        'rental_date',
        'status',
        'delivery_assigned_to',
        'photo_url',
        'payment_status',
    ];

    protected $casts = [
        'items' => 'array',
        'bundles' => 'array',
        'rental_date' => 'date',
        'status' => RentalStatus::class,
        'payment_status' => 'string',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
