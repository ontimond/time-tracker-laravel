<?php

namespace Tests\Feature;

use App\Http\Services\ProviderService;
use App\Http\Strategies\ClockifyStrategy;
use App\Http\Strategies\ProviderStrategyFactory;
use App\Models\Provider;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ProviderServiceTest extends TestCase
{
    use RefreshDatabase;

    private function get_mocked_factory(): MockInterface
    {
        return $this->mock(ProviderStrategyFactory::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')->andReturn($this->mock(ClockifyStrategy::class, function (MockInterface $mock) {
                $mock->shouldReceive('create')->andReturn([
                    'id' => '123',
                    'description' => 'Created description',
                ]);
                $mock->shouldReceive('update')->andReturn([
                    'id' => '123',
                    'description' => 'Updated description',
                ]);
                $mock->shouldReceive('delete');
            }));
        });
    }

    public function test_provider_service_can_attach_provider_to_time_entry(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $providerService = new ProviderService($this->get_mocked_factory());

        $providerService->attach($timeEntry, $provider);

        $this->assertDatabaseHas('provider_time_entry', [
            'provider_id' => $provider->id,
            'time_entry_id' => $timeEntry->id,
        ]);
    }

    public function test_provider_service_can_detach_provider_from_time_entry(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $providerService = new ProviderService($this->get_mocked_factory());

        $providerService->attach($timeEntry, $provider);

        $providerService->detach($timeEntry, $provider);

        $this->assertDatabaseMissing('provider_time_entry', [
            'provider_id' => $provider->id,
            'time_entry_id' => $timeEntry->id,
        ]);
    }

    public function test_provider_service_can_update_time_entry(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $providerService = new ProviderService($this->get_mocked_factory());

        $providerService->attach($timeEntry, $provider);
        $providerService->update($timeEntry, $provider);

        $this->assertDatabaseHas('provider_time_entry', [
            'provider_id' => $provider->id,
            'time_entry_id' => $timeEntry->id,
            'data' => json_encode([
                'id' => '123',
                'description' => 'Updated description',
            ])
        ]);
    }
}