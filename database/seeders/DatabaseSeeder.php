<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Juan David',
            'email' => 'juan.melo@elaniin.com',
            'password' => '$2y$10$7.DRVWOGW.QhzlPCiqdW2u2H48wHeP0UZqXAVrknDRzgBw6GRYkzC'
        ]);

        $this->call([
            ProviderSeeder::class,
        ]);
    }
}