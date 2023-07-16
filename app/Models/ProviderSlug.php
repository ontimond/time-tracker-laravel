<?php

namespace App\Models;

enum ProviderSlug: string
{
    case Clockify = 'clockify';
    case Toggl = 'toggl';
}