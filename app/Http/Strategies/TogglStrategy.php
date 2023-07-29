<?php

namespace App\Http\Strategies;

use App\Models\Provider;
use App\Models\TimeEntry;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class TogglStrategy implements ProviderStrategy
{
    private PendingRequest $client;

    public function __construct(private Provider $provider)
    {
        $this->client = Http::withHeaders([
            'Content-Type' => 'application/json',

        ])
            ->withBasicAuth($provider->config['api_token'], 'api_token')
            ->baseUrl('https://api.track.toggl.com/api/v9');
    }

    public function get(TimeEntry $timeEntry): array
    {
        // $data = $timeEntry->getProviderData($this->provider->id);
        // $workspaceId = $this->provider->config['workspace_id'];

        // $response = $this->client->get("workspaces/$workspaceId/time-entries/{$data['id']}");

        // if ($response->status() === 404) {
        //     throw new \Exception('Time entry not found');
        // }

        // // Get the data from the response
        // $data = $response->json();

        // // Return the data in the format we want
        // return $data;

        throw new \Exception('Not implemented');
    }

    public function create(TimeEntry $timeEntry): array
    {
        $workspaceId = $this->provider->config['workspace_id'];
        $projectId = $this->provider->config['project_id'] ?? null;

        $request = [
            'start' => $timeEntry->start,
            'stop' => $timeEntry->stop,
            'billable' => $timeEntry->billable,
            'description' => $timeEntry->description,
            'project_id' => $projectId,
            'workspace_id' => $workspaceId,
            // Required also in the request body for some reason
            'created_with' => 'Time Tracker'
        ];

        $response = $this->client->post("workspaces/$workspaceId/time_entries", $request);

        if ($response->status() !== 200) {
            throw new \Exception('Time entry not created');
        }

        $data = $response->json();


        return $data;
    }

    public function update(TimeEntry $timeEntry): array
    {
        $data = $timeEntry->getProviderData($this->provider->id);
        $workspaceId = $this->provider->config['workspace_id'];
        $projectId = $this->provider->config['project_id'] ?? null;

        $request = [
            'start' => $timeEntry->start,
            'stop' => $timeEntry->stop,
            'billable' => $timeEntry->billable,
            'description' => $timeEntry->description,
            'project_id' => $projectId,
            'workspace_id' => $workspaceId, // Required also in the request body for some reason
        ];

        $response = $this->client->put("workspaces/$workspaceId/time_entries/{$data['id']}", $request);

        $data = $response->json();

        return $data;
    }

    public function delete(TimeEntry $timeEntry): void
    {
        $data = $timeEntry->getProviderData($this->provider->id);
        $workspaceId = $this->provider->config['workspace_id'];

        $response = $this->client->delete("workspaces/$workspaceId/time_entries/{$data['id']}");

        if ($response->status() === 404) {
            throw new \Exception('Time entry not found');
        }

        return;
    }
}