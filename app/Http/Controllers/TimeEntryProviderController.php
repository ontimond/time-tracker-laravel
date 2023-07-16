<?php

namespace App\Http\Controllers;

use App\Http\Services\ProviderService;
use App\Models\Provider;
use App\Models\TimeEntry;

class TimeEntryProviderController extends Controller
{

    public function __construct(private ProviderService $providerService)
    {
    }

    public function attach(TimeEntry $timeEntry, Provider $provider)
    {
        $this->providerService->attach($timeEntry, $provider);
        return redirect()->route('dashboard');
    }

    public function detach(TimeEntry $timeEntry, Provider $provider)
    {
        $this->providerService->detach($timeEntry, $provider);

        return redirect()->route('dashboard');
    }

}