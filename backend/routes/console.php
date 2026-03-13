<?php

use App\Console\Commands\CheckProxyStatuses;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CheckProxyStatuses::class)->everyFiveMinutes();