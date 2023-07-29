<?php

namespace App\Http\Strategies;

use App\Models\Provider;
use App\Models\ProviderSlug;

class ProviderStrategyFactory
{

    public function create(Provider $provider): ProviderStrategy
    {
        switch ($provider->slug) {
            case ProviderSlug::Clockify:
                return new ClockifyStrategy($provider);
            case ProviderSlug::Toggl:
                return new TogglStrategy($provider);
            default:
                throw new \InvalidArgumentException("Invalid provider slug: {$provider->slug}");
        }
    }
}