<?php

namespace App\Enums;

enum Status: bool
{
    case ACTIVE = true;
    case INACTIVE = false;
}
