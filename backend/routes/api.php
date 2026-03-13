<?php

use App\Http\Controllers\Api\V1\ProxyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('proxies', ProxyController::class);
    Route::post('proxies/{proxy}/check', [ProxyController::class, 'check'])->name('proxies.check');
});