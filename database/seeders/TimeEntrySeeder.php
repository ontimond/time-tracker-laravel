<?php

namespace Database\Seeders;

use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Seeder;

class TimeEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        TimeEntry::factory()
            ->count(10)
            ->create(
                [
                    'user_id' => $user->id,
                ]
            );
    }
}