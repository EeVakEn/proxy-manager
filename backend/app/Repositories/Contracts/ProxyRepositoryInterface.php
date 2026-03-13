<?php

namespace App\Repositories\Contracts;

use App\Enums\ProxyStatus;
use App\Models\Proxy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProxyRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function findById(int $id): Proxy;

    public function create(array $data): Proxy;

    public function update(Proxy $proxy, array $data): Proxy;

    public function delete(Proxy $proxy): void;

    public function markChecking(Proxy $proxy): void;

    public function updateStatus(Proxy $proxy, ProxyStatus $status): void;
}