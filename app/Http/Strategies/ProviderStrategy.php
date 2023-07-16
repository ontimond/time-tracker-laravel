<?php

namespace App\Http\Strategies;

use App\Models\TimeEntry;

interface ProviderStrategy
{
    function get(TimeEntry $timeEntry): array;
    function create(TimeEntry $timeEntry): array;
    function update(TimeEntry $timeEntry): array;
    function delete(TimeEntry $timeEntry): void;
}