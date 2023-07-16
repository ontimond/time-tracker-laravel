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
            // Agrega más casos según sea necesario
            default:
                throw new \InvalidArgumentException("Invalid provider slug: {$provider->slug}");
        }
    }
}