<?php

namespace App\Http\Strategies;

use App\Models\Provider;
use App\Models\TimeEntry;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ClockifyStrategy implements ProviderStrategy
{
    private PendingRequest $client;

    public function __construct(private Provider $provider)
    {
        $this->client = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Api-Key' => $provider->config['api_key']
        ])->baseUrl('https://api.clockify.me/api/v1');
    }

    public function get(TimeEntry $timeEntry): array
    {
        $data = $timeEntry->getProviderData($this->provider->id);
        $workspaceId = $this->provider->config['workspace_id'];

        $response = $this->client->get("workspaces/$workspaceId/time-entries/{$data['id']}");

        if ($response->status() === 404) {
            throw new \Exception('Time entry not found');
        }

        // Get the data from the response
        $data = $response->json();

        // Return the data in the format we want
        return $data;
    }

    public function create(TimeEntry $timeEntry): array
    {
        $workspaceId = $this->provider->config['workspace_id'];
        $projectId = $this->provider->config['project_id'];

        $request = [
            'start' => $timeEntry->start,
            'end' => $timeEntry->stop,
            'billable' => $timeEntry->billable,
            'description' => $timeEntry->description,
            'projectId' => $projectId
        ];

        $response = $this->client->post("workspaces/$workspaceId/time-entries", $request);

        if ($response->status() !== 201) {
            throw new \Exception('Time entry not created');
        }

        $data = $response->json();

        return $data;
    }

    public function update(TimeEntry $timeEntry): array
    {
        $data = $timeEntry->getProviderData($this->provider->id);
        $workspaceId = $this->provider->config['workspace_id'];
        $projectId = $this->provider->config['project_id'];

        $request = [
            'start' => $timeEntry->start,
            'end' => $timeEntry->stop,
            'billable' => $timeEntry->billable,
            'description' => $timeEntry->description,
            'projectId' => $projectId
        ];

        $response = $this->client->put("workspaces/$workspaceId/time-entries/{$data['id']}", $request);

        $data = $response->json();

        return $data;
    }

    public function delete(TimeEntry $timeEntry): void
    {
        $data = $timeEntry->getProviderData($this->provider->id);
        $workspaceId = $this->provider->config['workspace_id'];

        $response = $this->client->delete("workspaces/$workspaceId/time-entries/{$data['id']}");

        if ($response->status() === 404) {
            throw new \Exception('Time entry not found');
        }

        return;
    }
}