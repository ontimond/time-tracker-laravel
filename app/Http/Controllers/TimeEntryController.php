<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\TimeEntryStoreRequest;
use App\Http\Requests\TimeEntryUpdateRequest;
use App\Http\Services\ProviderTimeEntryService;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TimeEntryController extends Controller
{
    public function __construct(private ProviderTimeEntryService $providerTimeEntryService)
    {
    }

    public function index(Request $request)
    {
        return Inertia::render('TimeEntries/Index', [
            'timeEntriesGroupedByDay' => $request->user()->timeEntriesGroupedByDay(),
            'providers' => $request->user()->providers,
        ]);
    }

    public function create()
    {
        return Inertia::render('TimeEntries/Create');
    }

    public function store(TimeEntryStoreRequest $request)
    {
        $timeEntry = new TimeEntry($request->validated());
        $timeEntry->user()->associate($request->user());
        $timeEntry->save();

        return Redirect::route('time-entries.index');
    }

    public function edit(TimeEntry $timeEntry)
    {
        return Inertia::render('TimeEntries/Edit', [
            'timeEntry' => $timeEntry,
        ]);
    }

    public function update(TimeEntryUpdateRequest $request, TimeEntry $timeEntry)
    {
        $timeEntry->fill($request->validated());
        $timeEntry->save();

        // For each provider in time entry update the data
        foreach ($timeEntry->providers as $provider) {
            $this->providerTimeEntryService->update($timeEntry, $provider);
        }

        return Redirect::route('time-entries.index');
    }

    public function destroy(TimeEntry $timeEntry)
    {
        foreach ($timeEntry->providers as $provider) {
            $this->providerTimeEntryService->destroy($timeEntry, $provider);
        }

        $timeEntry->delete();

        return Redirect::route('time-entries.index');
    }

}