<?php

namespace App\Http\Services;

use DB;
use App\Http\Strategies\ProviderStrategyFactory;
use App\Models\Provider;
use App\Models\TimeEntry;

class ProviderTimeEntryService
{
    public function __construct(
        private ProviderStrategyFactory $factory
    ) {
    }

    public function attach(TimeEntry $timeEntry, Provider $provider): void
    {
        // In case of an exception, the relation will not be created
        DB::transaction(function () use ($timeEntry, $provider) {
            $strategy = $this->factory->create($provider);
            $data = $strategy->create($timeEntry);

            $timeEntry->providers()->attach($provider->id, ['data' => $data]);
        });
    }


    public function detach(TimeEntry $timeEntry, Provider $provider): void
    {
        // In case of an exception, the relation will not be deleted
        DB::transaction(function () use ($timeEntry, $provider) {
            $strategy = $this->factory->create($provider);
            $strategy->delete($timeEntry);

            $timeEntry->providers()->detach($provider->id);
        });
    }

    public function update(TimeEntry $timeEntry, Provider $provider): void
    {
        // In case of an exception, the relation will not be updated
        DB::transaction(function () use ($timeEntry, $provider) {
            $strategy = $this->factory->create($provider);
            $data = $strategy->update($timeEntry);

            $timeEntry->providers()->updateExistingPivot($provider->id, ['data' => $data]);
        });
    }

}