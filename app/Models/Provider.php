<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'config'
    ];

    protected $casts = [
        'slug' => ProviderSlug::class,
        'config' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeEntries()
    {
        return $this->belongsToMany(TimeEntry::class)->using(ProviderTimeEntry::class);
    }
}