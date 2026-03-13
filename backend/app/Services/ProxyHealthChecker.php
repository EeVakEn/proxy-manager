<?php

namespace App\Services;

use App\Enums\ProxyType;
use App\Models\Proxy;

class ProxyHealthChecker
{
    private const CHECK_URL = 'https://httpbin.org/ip';
    private const TIMEOUT   = 10;

    public function check(Proxy $proxy): bool
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => self::CHECK_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => self::TIMEOUT,
            CURLOPT_PROXY          => $proxy->host,
            CURLOPT_PROXYPORT      => $proxy->port,
            CURLOPT_PROXYTYPE      => $this->resolveCurlProxyType($proxy->type),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        if ($proxy->login && $proxy->password) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, "{$proxy->login}:{$proxy->password}");
        }

        curl_exec($ch);

        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_errno($ch);

        curl_close($ch);

        return $error === 0 && $httpCode >= 200 && $httpCode < 300;
    }

    private function resolveCurlProxyType(ProxyType $type): int
    {
        return match($type) {
            ProxyType::Http   => CURLPROXY_HTTP,
            ProxyType::Https  => CURLPROXY_HTTPS,
            ProxyType::Socks5 => CURLPROXY_SOCKS5,
        };
    }
}