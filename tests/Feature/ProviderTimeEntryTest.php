<?php

namespace Tests\Feature;

use App\Http\Services\ProviderTimeEntryService;
use App\Models\Provider;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class ProviderTimeEntryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(ProviderTimeEntryService::class, function (MockInterface $mock) {
            $mock->shouldReceive('attach');
            $mock->shouldReceive('detach');
        });

    }

    public function test_provider_time_entry_attach(): void
    {

        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/time-entries/' . $timeEntry->id . '/providers/' . $provider->id . '/attach');

        $response->assertRedirect('/time-entries');
    }


    public function test_provider_time_entry_detach(): void
    {

        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $timeEntry->providers()->attach($provider, [
            'data' => [
                'description' => 'Test description'
            ],
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/time-entries/' . $timeEntry->id . '/providers/' . $provider->id . '/detach');

        $response->assertRedirect('/time-entries');
    }
}