<?php

namespace Tests\Feature;

use App\Models\Provider;
use App\Models\ProviderSlug;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_providers_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/providers');

        $response->assertStatus(200);
    }

    public function test_create_provider_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/providers/create');

        $response->assertStatus(200);
    }

    public function test_provider_can_be_created(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->raw();

        $response = $this
            ->actingAs($user)
            ->post('/providers', $provider);

        $response->assertRedirect('/providers');

        $this->assertDatabaseHas('providers', [
            'slug' => $provider['slug'],
            'user_id' => $user->id,
        ]);
    }

    public function test_update_provider_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/providers/' . $provider->id . '/edit');

        $response->assertStatus(200)->assertSee($provider->slug);
    }

    public function test_provider_can_be_updated(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
        ]);

        $provider->slug = ProviderSlug::Toggl;

        $response = $this
            ->actingAs($user)
            ->patch('/providers/' . $provider->id, $provider->toArray());


        $response->assertRedirect('/providers');

        $this->assertDatabaseHas('providers', [
            'id' => $provider->id,
            'slug' => ProviderSlug::Toggl,
        ]);
    }

    public function test_provider_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $provider = Provider::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/providers/' . $provider->id);

        $response->assertRedirect('/providers');

        $this->assertDatabaseMissing('providers', [
            'id' => $provider->id,
        ]);
    }

}