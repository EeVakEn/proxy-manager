<?php

namespace App\Jobs;

use App\Enums\ProxyStatus;
use App\Models\Proxy;
use App\Repositories\Contracts\ProxyRepositoryInterface;
use App\Services\ProxyHealthChecker;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class CheckProxyHealth implements ShouldQueue
{
    use Queueable;

    public int $tries   = 3;
    public int $timeout = 15;

    public function __construct(
        private readonly Proxy $proxy,
    ) {}

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping("proxy:{$this->proxy->id}"))->releaseAfter(60),
        ];
    }

    public function handle(ProxyHealthChecker $checker, ProxyRepositoryInterface $repository): void
    {
        $status = $checker->check($this->proxy)
            ? ProxyStatus::Active
            : ProxyStatus::Inactive;

        $repository->updateStatus($this->proxy, $status);
    }

    public function backoff(): array
    {
        return [10, 60];
    }
}