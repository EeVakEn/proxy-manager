<?php

namespace App\Models;

use App\Enums\ProxyStatus;
use App\Enums\ProxyType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    use HasFactory;
    protected $fillable = [
        'host',
        'port',
        'type',
        'login',
        'password',
        'status',
        'last_checked_at',
    ];

    protected $casts = [
        'port'            => 'integer',
        'type'            => ProxyType::class,
        'status'          => ProxyStatus::class,
        'password'        => 'encrypted',
        'last_checked_at' => 'datetime',
    ];

    public function scopeStale(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('last_checked_at')
              ->orWhere('last_checked_at', '<=', now()->subMinutes(5));
        });
    }
}