<?php

namespace App\Enums;

enum ProxyType: string
{
    case Http   = 'http';
    case Https  = 'https';
    case Socks5 = 'socks5';

    public function label(): string
    {
        return match($this) {
            self::Http   => 'HTTP',
            self::Https  => 'HTTPS',
            self::Socks5 => 'SOCKS5',
        };
    }
}