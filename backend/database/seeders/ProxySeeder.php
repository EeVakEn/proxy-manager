<?php

namespace Database\Seeders;

use App\Models\Proxy;
use Illuminate\Database\Seeder;

class ProxySeeder extends Seeder
{
    public function run(): void
    {
        Proxy::factory(6)->active()->create();
        Proxy::factory(3)->inactive()->create();
        Proxy::factory(2)->checking()->create();
        Proxy::factory(2)->withAuth()->active()->create();
    }
}