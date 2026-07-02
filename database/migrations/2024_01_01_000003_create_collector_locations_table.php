<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collector_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collector_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_available')->default(false);
            $table->double('lat');
            $table->double('lng');
            $table->timestamps();
        });

        // Add a real PostGIS geography column and keep it in sync via a generated expression.
        // geography(Point,4326) = lat/lng on Earth's surface, SRID 4326 = standard GPS coordinates.
        DB::statement('ALTER TABLE collector_locations ADD COLUMN geo geography(Point,4326)
            GENERATED ALWAYS AS (ST_SetSRID(ST_MakePoint(lng, lat), 4326)::geography) STORED');

        // Spatial index -> makes "nearest collector" queries fast even with thousands of rows
        DB::statement('CREATE INDEX collector_locations_geo_idx ON collector_locations USING GIST (geo)');
    }

    public function down(): void
    {
        Schema::dropIfExists('collector_locations');
    }
};
