<?php

namespace Tests\Feature;

use App\Models\User;
use \App\Models\TimeEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class TimeEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_time_entries_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/time-entries');

        $response->assertStatus(200);
    }

    public function test_create_time_entry_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/time-entries/create');

        $response->assertStatus(200);
    }

    public function test_time_entries_are_ordered_by_created_at(): void
    {
        $user = User::factory()->create();

        $timeEntry1 = TimeEntry::factory()->create([
            'user_id' => $user->id,
            'start' => "2021-01-01 10:00:00",
        ]);

        $timeEntry2 = TimeEntry::factory()->create([
            'user_id' => $user->id,
            'start' => "2021-01-02 11:00:00",
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/time-entries');

        $response->assertStatus(200);

        // Check that the time entries are ordered by start and grouped by day
        $response->assertInertia(
            fn(Assert $page) => $page
                ->has('timeEntriesGroupedByDay', 2)
                ->where('timeEntriesGroupedByDay.2021-01-02.0.id', $timeEntry2->id)
                ->where('timeEntriesGroupedByDay.2021-01-01.0.id', $timeEntry1->id)
        );
    }

    public function test_time_entry_can_be_created(): void
    {
        $user = User::factory()->create();

        $timeEntry = TimeEntry::factory()->raw();

        $response = $this
            ->actingAs($user)
            ->post('/time-entries', $timeEntry);

        $response->assertRedirect('/time-entries');

        $this->assertDatabaseHas('time_entries', [
            'description' => $timeEntry['description'],
            'user_id' => $user->id,
        ]);
    }

    public function test_update_time_entry_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/time-entries/' . $timeEntry->id . '/edit');

        $response->assertStatus(200);
    }

    public function test_time_entry_can_be_updated(): void
    {
        $user = User::factory()->create();

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $timeEntry->description = 'Updated description';

        $response = $this
            ->actingAs($user)
            ->patch('/time-entries/' . $timeEntry->id, $timeEntry->toArray());

        $response->assertRedirect('/time-entries');

        $this->assertDatabaseHas('time_entries', [
            'id' => $timeEntry->id,
            'description' => 'Updated description',
        ]);
    }

    public function test_time_entry_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/time-entries/' . $timeEntry->id);

        $response->assertRedirect('/time-entries');

        $this->assertDatabaseMissing('time_entries', [
            'id' => $timeEntry->id,
        ]);
    }
}