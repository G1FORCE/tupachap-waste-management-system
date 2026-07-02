<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * PostGIS gives us the `geometry`/`geography` column types and
     * spatial functions like ST_DWithin, ST_Distance, ST_MakePoint.
     * Without this, we could not do "find nearest collector" queries
     * efficiently at the database level.
     */
    public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS postgis');
    }

    public function down(): void
    {
        DB::statement('DROP EXTENSION IF EXISTS postgis');
    }
};
