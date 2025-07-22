<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ItemStatus;

class Bundle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => ItemStatus::class,
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'bundle_item')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
