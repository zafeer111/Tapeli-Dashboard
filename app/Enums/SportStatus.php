<?php

// ------------------
// Enum for Sport Status
// ------------------

namespace App\Enums;

enum SportStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}