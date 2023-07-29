<?php

namespace App\Http\Controllers;

use App\Http\Services\ProviderTimeEntryService;
use App\Models\Provider;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\Redirect;

class ProviderTimeEntryController extends Controller
{

    public function __construct(private ProviderTimeEntryService $providerTimeEntryService)
    {
    }

    public function attach(TimeEntry $timeEntry, Provider $provider)
    {
        $this->providerTimeEntryService->attach($timeEntry, $provider);
        return Redirect::route('time-entries.index');
    }

    public function detach(TimeEntry $timeEntry, Provider $provider)
    {
        $this->providerTimeEntryService->detach($timeEntry, $provider);

        return Redirect::route('time-entries.index');
    }

}