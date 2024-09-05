<?php

namespace App\Enums;

enum UserRoles: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case USER = 'user';
}
