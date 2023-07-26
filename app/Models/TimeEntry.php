<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected $appends = [
        'duration',
    ];

    public function duration(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->calculateDuration()
        );
    }

    private function calculateDuration(): int
    {
        return abs($this->start->getTimestamp() - ($this->stop?->getTimestamp() ?? now()->getTimestamp()));
    }

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