<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProviderTimeEntry extends Pivot
{
    protected $casts = [
        'data' => 'array',
    ];

}