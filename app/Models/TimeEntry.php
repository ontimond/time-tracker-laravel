<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'billable',
        'start',
        'stop',
    ];

    protected $casts = [
        'start' => 'datetime',
        'stop' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function providers()
    {
        return $this->belongsToMany(Provider::class)->using(ProviderTimeEntry::class);
    }

    public function getProviderData(int $providerId): array
    {
        return $this->providers()
            ->wherePivot('provider_id', $providerId)
            ->withPivot('data')
            ->firstOrFail()
            ->pivot
            ->data;
    }
}