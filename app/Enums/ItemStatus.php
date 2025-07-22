<?php

// ------------------
// Enum for Item Status
// ------------------

namespace App\Enums;

enum ItemStatus: string
{
    case AVAILABLE = 'available';
    case UNAVAILABLE = 'unavailable';
}