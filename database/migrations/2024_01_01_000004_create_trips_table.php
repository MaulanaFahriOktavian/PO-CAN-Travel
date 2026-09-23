<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->restrictOnDelete();
            $table->foreignId('route_id')->constrained('routes')->restrictOnDelete();
            $table->dateTime('departure_at');
            $table->dateTime('arrival_at');
            $table->unsignedBigInteger('price'); // nominal rupiah, tanpa decimal
            $table->enum('status', ['scheduled', 'departed', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();

            $table->index(['route_id', 'departure_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
