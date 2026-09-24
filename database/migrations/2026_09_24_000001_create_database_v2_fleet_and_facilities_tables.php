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
        // 1. Perluas tabel buses dengan bus_type dan description
        Schema::table('buses', function (Blueprint $table) {
            $table->string('bus_type')->default('Executive Class')->after('total_seats');
            $table->text('description')->nullable()->after('bus_type');
        });

        // 2. Tabel facilities (Master fasilitas resmi armada)
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. Tabel bus_facilities (Pivot many-to-many armada dan fasilitas)
        Schema::create('bus_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['bus_id', 'facility_id']);
        });

        // 4. Tabel bus_images (Galeri foto nyata armada)
        Schema::create('bus_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_images');
        Schema::dropIfExists('bus_facilities');
        Schema::dropIfExists('facilities');

        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn(['bus_type', 'description']);
        });
    }
};
