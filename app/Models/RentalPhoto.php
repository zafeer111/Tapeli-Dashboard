<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentalPhoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rental_id',
        'photo_path',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}