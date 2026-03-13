<?php

namespace App\Enums;

enum ProxyStatus: string
{
    case Active   = 'active';
    case Inactive = 'inactive';
    case Checking = 'checking';
}