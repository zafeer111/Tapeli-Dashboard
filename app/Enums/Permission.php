<?php


namespace App\Enums;

enum Permission: string
{
    case SUPER_ADMIN = 'super_admin';
    case MANAGER = 'manager';
    case USER = 'user';
}