<?php

namespace App\Console\Commands;

use App\Jobs\CheckProxyHealth;
use App\Models\Proxy;
use App\Repositories\Contracts\ProxyRepositoryInterface;
use Illuminate\Console\Command;

class CheckProxyStatuses extends Command
{
    protected $signature   = 'proxy:check-all';
    protected $description = 'Dispatch health-check jobs for all stale proxies';

    public function handle(ProxyRepositoryInterface $repository): int
    {
        $stale = Proxy::stale()->get();

        if ($stale->isEmpty()) {
            $this->info('No stale proxies found.');
            return self::SUCCESS;
        }

        foreach ($stale as $proxy) {
            $repository->markChecking($proxy);
            CheckProxyHealth::dispatch($proxy)->onQueue('proxies');
        }

        $this->info("Dispatched health checks for {$stale->count()} proxies.");

        return self::SUCCESS;
    }
}