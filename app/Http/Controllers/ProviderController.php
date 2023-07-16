<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProviderStoreRequest;
use App\Http\Requests\ProviderUpdateRequest;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Providers/Index', [
            'providers' => $request->user()->providers()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Providers/Create');
    }

    public function store(ProviderStoreRequest $request)
    {
        $provider = new Provider($request->validated());
        $provider->user()->associate($request->user());
        $provider->save();

        return Redirect::route('providers.index');
    }

    public function edit(Request $request, Provider $provider)
    {
        return Inertia::render('Providers/Edit', [
            'provider' => $provider,
        ]);
    }

    public function update(ProviderUpdateRequest $request, Provider $provider)
    {
        $provider->fill($request->validated());
        $provider->save();

        return Redirect::route('providers.index');
    }

    public function destroy(Request $request, Provider $provider)
    {
        $provider->delete();

        return Redirect::route('providers.index');
    }

}