<?php

namespace Database\Factories;

use App\Enums\ProxyStatus;
use App\Enums\ProxyType;
use App\Models\Proxy;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProxyFactory extends Factory
{
    protected $model = Proxy::class;

    public function definition(): array
    {
        return [
            'host'            => fake()->ipv4(),
            'port'            => fake()->numberBetween(1024, 65535),
            'type'            => fake()->randomElement(ProxyType::cases())->value,
            'login'           => fake()->optional(0.4)->userName(),
            'password'        => null,
            'status'          => fake()->randomElement(ProxyStatus::cases())->value,
            'last_checked_at' => fake()->optional(0.8)->dateTimeBetween('-1 hour', 'now'),
        ];
    }

    public function withAuth(): static
    {
        return $this->state(fn () => [
            'login'    => fake()->userName(),
            'password' => fake()->password(8),
        ]);
    }

    public function active(): static
    {
        return $this->state(fn () => [
            'status'          => ProxyStatus::Active->value,
            'last_checked_at' => now()->subMinutes(fake()->numberBetween(1, 4)),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'status'          => ProxyStatus::Inactive->value,
            'last_checked_at' => now()->subMinutes(fake()->numberBetween(1, 30)),
        ]);
    }

    public function checking(): static
    {
        return $this->state(fn () => [
            'status'          => ProxyStatus::Checking->value,
            'last_checked_at' => null,
        ]);
    }
}