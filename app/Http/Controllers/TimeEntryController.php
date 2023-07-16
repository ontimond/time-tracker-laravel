<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\TimeEntryStoreRequest;
use App\Http\Requests\TimeEntryUpdateRequest;
use App\Http\Services\ProviderService;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TimeEntryController extends Controller
{
    public function __construct(private ProviderService $providerService)
    {
    }

    public function index(Request $request)
    {
        return Inertia::render('TimeEntries/Index', [
            'timeEntries' => $request->user()->timeEntries()->get(),
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
            $this->providerService->update($timeEntry, $provider);
        }

        return Redirect::route('time-entries.index');
    }

    public function destroy(TimeEntry $timeEntry)
    {
        $timeEntry->delete();

        return Redirect::route('time-entries.index');
    }

}