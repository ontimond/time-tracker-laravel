<?php

namespace Tests\Feature;

use App\Http\Strategies\ClockifyStrategy;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\TimeEntry;

class ClockifyStrategyTest extends TestCase
{
    use RefreshDatabase;

    private array $clearTimeEntries = [];

    private array $clockifyConfig = [
        'api_key' => 'OWVjNGI2ZTUtZjZkMS00NjBmLThlZGItZmFmZTExMmVkMGI3',
        'workspace_id' => '63f9136d841bbb12719a8816',
        'project_id' => '64b0798e462ec532dd64fcec',
    ];

    public function test_strategy_can_be_created(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
        ]);

        $strategy = new ClockifyStrategy($provider);

        $this->assertInstanceOf(ClockifyStrategy::class, $strategy);
    }

    public function test_strategy_can_create_time_entry(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
            'config' => $this->clockifyConfig,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $strategy = new ClockifyStrategy($provider);

        $data = $strategy->create($timeEntry);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data);

        $timeEntry->providers()->attach($provider->id, [
            'data' => [
                'id' => $data['id'],
            ]
        ]);

        // Save the ID for tear down later
        array_push($this->clearTimeEntries, fn() => $strategy->delete($timeEntry));
    }

    public function test_strategy_can_update_time_entry(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
            'config' => $this->clockifyConfig,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $strategy = new ClockifyStrategy($provider);

        $data = $strategy->create($timeEntry);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data);

        $timeEntry->providers()->attach($provider->id, [
            'data' => [
                'id' => $data['id'],
            ]
        ]);

        $timeEntry->description = 'Updated description';

        $data = $strategy->update($timeEntry);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('description', $data);

        $this->assertEquals('Updated description', $data['description']);

        // Save the ID for tear down later
        array_push($this->clearTimeEntries, fn() => $strategy->delete($timeEntry));
    }

    public function test_strategy_can_delete_time_entry(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
            'config' => $this->clockifyConfig,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $strategy = new ClockifyStrategy($provider);

        $data = $strategy->create($timeEntry);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data);

        $timeEntry->providers()->attach($provider->id, [
            'data' => [
                'id' => $data['id'],
            ]
        ]);

        $strategy->delete($timeEntry);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        foreach ($this->clearTimeEntries as $clear) {
            $clear();
        }
    }
}