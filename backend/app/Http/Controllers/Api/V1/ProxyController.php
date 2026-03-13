<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProxyIndexRequest;
use App\Http\Requests\StoreProxyRequest;
use App\Http\Requests\UpdateProxyRequest;
use App\Http\Resources\ProxyResource;
use App\Models\Proxy;
use App\Repositories\Contracts\ProxyRepositoryInterface;
use App\Services\ProxyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProxyController extends Controller
{
    public function __construct(
        private readonly ProxyRepositoryInterface $repository,
        private readonly ProxyService $service,
    ) {}

    public function index(ProxyIndexRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        return ProxyResource::collection(
            $this->repository->paginate($validated['per_page'], $validated['page'])
        );
    }

    public function store(StoreProxyRequest $request): ProxyResource
    {
        $proxy = $this->service->store($request->validated());

        return new ProxyResource($proxy);
    }

    public function show(Proxy $proxy): ProxyResource
    {
        return new ProxyResource($proxy);
    }

    public function update(UpdateProxyRequest $request, Proxy $proxy): ProxyResource
    {
        $proxy = $this->service->update($proxy, $request->validated());

        return new ProxyResource($proxy);
    }

    public function destroy(Proxy $proxy): JsonResponse
    {
        $this->service->destroy($proxy);

        return response()->json(null, 204);
    }

    public function check(Proxy $proxy): ProxyResource
    {
        $proxy = $this->service->triggerManualCheck($proxy);

        return new ProxyResource($proxy);
    }
}