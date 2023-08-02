<?php

namespace Tests\Unit;

use App\Http\Strategies\ClockifyStrategy;
use App\Http\Strategies\ProviderStrategyFactory;
use App\Models\Provider;
use App\Models\ProviderSlug;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderStrategyFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_provider_strategy_factory_can_create_strategy(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
            'slug' => ProviderSlug::Clockify,
        ]);

        $factory = new ProviderStrategyFactory();

        $strategy = $factory->create($provider);

        $this->assertInstanceOf(ClockifyStrategy::class, $strategy);
    }
}