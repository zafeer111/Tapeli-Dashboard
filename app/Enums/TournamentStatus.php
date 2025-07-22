<?php

// ------------------
// Enum for Tournament Status
// ------------------

namespace App\Enums;

enum TournamentStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case COMPLETED = 'completed';
}
