<?php

// ------------------
// Enum for Rental Status
// ------------------

namespace App\Enums;

enum RentalStatus: string
{
    case PENDING = 'pending';
    case DELIVERED = 'delivered';
    case PICKED_UP = 'picked_up';
    case RETURNED = 'returned';
}