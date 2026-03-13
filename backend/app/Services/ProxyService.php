<?php

namespace App\Services;

use App\Jobs\CheckProxyHealth;
use App\Models\Proxy;
use App\Repositories\Contracts\ProxyRepositoryInterface;
use BackedEnum;

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

        return $proxy->fresh() ?? $proxy;
    }

    private function connectionChanged(Proxy $proxy, array $data): bool
    {
        return collect(['host', 'port', 'type', 'login', 'password'])
            ->filter(fn (string $field) => isset($data[$field]))
            ->some(function (string $field) use ($proxy, $data): bool {
                $current = $proxy->{$field};
                $current = $current instanceof BackedEnum ? $current->value : $current;

                return (string) $current !== (string) $data[$field];
            });
    }
}