<?php

namespace App\Repositories;

use App\Enums\ProxyStatus;
use App\Models\Proxy;
use App\Repositories\Contracts\ProxyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProxyRepository implements ProxyRepositoryInterface
{
    private const CACHE_KEY = 'proxies.all';
    private const CACHE_TTL = 300;

    public function all(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Proxy::orderByDesc('created_at')->get();
        });
    }

    public function findById(int $id): Proxy
    {
        return Proxy::findOrFail($id);
    }

    public function create(array $data): Proxy
    {
        $proxy = Proxy::create($data);
        $this->flush();

        return $proxy;
    }

    public function update(Proxy $proxy, array $data): Proxy
    {
        $proxy->update($data);
        $this->flush();

        return $proxy->fresh();
    }

    public function delete(Proxy $proxy): void
    {
        $proxy->delete();
        $this->flush();
    }

    public function markChecking(Proxy $proxy): void
    {
        $proxy->update(['status' => ProxyStatus::Checking]);
        $this->flush();
    }

    public function updateStatus(Proxy $proxy, ProxyStatus $status): void
    {
        $proxy->update([
            'status'          => $status,
            'last_checked_at' => now(),
        ]);
        $this->flush();
    }

    private function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}