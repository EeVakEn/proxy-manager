<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProxyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'host'            => $this->host,
            'port'            => $this->port,
            'type'            => $this->type->value,
            'login'           => $this->login,
            'status'          => $this->status->value,
            'last_checked_at' => $this->last_checked_at?->toIso8601String(),
            'created_at'      => $this->created_at->toIso8601String(),
            'updated_at'      => $this->updated_at->toIso8601String(),
        ];
    }
}