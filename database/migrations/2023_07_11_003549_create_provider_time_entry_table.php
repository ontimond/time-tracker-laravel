<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('provider_time_entry', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Provider::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\TimeEntry::class)->constrained()->onDelete('cascade');
            $table->json('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_time_entries');
    }
};