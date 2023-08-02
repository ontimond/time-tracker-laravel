<?php

namespace Tests\Unit;

use App\Http\Strategies\TogglStrategy;
use App\Models\Provider;
use App\Models\ProviderSlug;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\TimeEntry;

class TogglStrategyTest extends TestCase
{
    use RefreshDatabase;

    private array $clearTimeEntries = [];

    private array $togglConfig = [
        'api_token' => 'f8ec196749e286400c2b9a2c2f3cddfe',
        'workspace_id' => 7097771,
        'project_id' => 193992250,
    ];

    public function test_strategy_can_be_created(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'slug' => ProviderSlug::Toggl,
            'config' => $this->togglConfig,
            'user_id' => $user->id,
        ]);

        $strategy = new TogglStrategy($provider);

        $this->assertInstanceOf(TogglStrategy::class, $strategy);
    }

    public function test_strategy_can_create_time_entry(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'slug' => ProviderSlug::Toggl,
            'config' => $this->togglConfig,
            'user_id' => $user->id,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $strategy = new TogglStrategy($provider);

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
            'config' => $this->togglConfig,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $strategy = new TogglStrategy($provider);

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
            'config' => $this->togglConfig,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $strategy = new TogglStrategy($provider);

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