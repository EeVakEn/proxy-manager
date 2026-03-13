<?php

namespace App\Repositories;

use App\Enums\ProxyStatus;
use App\Models\Proxy;
use App\Repositories\Contracts\ProxyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProxyRepository implements ProxyRepositoryInterface
{
    private const CACHE_TAG = 'proxies';
    private const CACHE_TTL = 300;

    public function all(): Collection
    {
        return Cache::tags(self::CACHE_TAG)->remember('all', self::CACHE_TTL, function () {
            return Proxy::orderByDesc('created_at')->get();
        });
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return Cache::tags(self::CACHE_TAG)->remember("page.{$page}.{$perPage}", self::CACHE_TTL, function () use ($perPage) {
            return Proxy::orderByDesc('created_at')->paginate($perPage);
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

        return $proxy->fresh() ?? $proxy;
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
        Cache::tags(self::CACHE_TAG)->flush();
    }
}
