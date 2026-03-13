<?php

namespace App\Services;

use App\Jobs\CheckProxyHealth;
use App\Models\Proxy;
use App\Repositories\Contracts\ProxyRepositoryInterface;

class ProxyService
{
    public function __construct(
        private readonly ProxyRepositoryInterface $repository,
    ) {}

    public function store(array $data): Proxy
    {
        $proxy = $this->repository->create($data);

        CheckProxyHealth::dispatch($proxy)->onQueue('proxies');

        return $proxy;
    }

    public function update(Proxy $proxy, array $data): Proxy
    {
        $connectionChanged = $this->connectionChanged($proxy, $data);

        $proxy = $this->repository->update($proxy, $data);

        if ($connectionChanged) {
            $this->repository->markChecking($proxy);
            CheckProxyHealth::dispatch($proxy)->onQueue('proxies');
        }

        return $proxy;
    }

    public function destroy(Proxy $proxy): void
    {
        $this->repository->delete($proxy);
    }

    public function triggerManualCheck(Proxy $proxy): Proxy
    {
        $this->repository->markChecking($proxy);

        CheckProxyHealth::dispatch($proxy)->onQueue('proxies');

        return $proxy->fresh();
    }

    private function connectionChanged(Proxy $proxy, array $data): bool
    {
        foreach (['host', 'port', 'type', 'login', 'password'] as $field) {
            if (isset($data[$field]) && (string) $proxy->{$field}?->value ?? $proxy->{$field} !== (string) $data[$field]) {
                return true;
            }
        }

        return false;
    }
}