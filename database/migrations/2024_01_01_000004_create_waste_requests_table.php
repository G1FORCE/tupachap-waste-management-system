<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('collector_id')->nullable()->constrained('users')->nullOnDelete();

            $table->double('pickup_lat');
            $table->double('pickup_lng');
            $table->text('address')->nullable();

            $table->enum('waste_type', ['general', 'recyclable', 'organic', 'hazardous'])->default('general');
            $table->decimal('estimated_kg', 8, 2)->default(10);

            // pending -> matched -> en_route -> collected -> paid -> cancelled
            $table->enum('status', ['pending', 'matched', 'en_route', 'collected', 'paid', 'cancelled'])
                  ->default('pending');

            $table->enum('type', ['on_demand', 'scheduled'])->default('on_demand');
            $table->dateTime('scheduled_at')->nullable();

            $table->string('proof_photo_path')->nullable(); // digital proof-of-service
            $table->decimal('price', 8, 2)->nullable();
            $table->string('payment_reference')->nullable(); // M-Pesa transaction id
            $table->timestamp('collected_at')->nullable();

            $table->timestamps();
        });

        DB::statement('ALTER TABLE waste_requests ADD COLUMN geo geography(Point,4326)
            GENERATED ALWAYS AS (ST_SetSRID(ST_MakePoint(pickup_lng, pickup_lat), 4326)::geography) STORED');

        DB::statement('CREATE INDEX waste_requests_geo_idx ON waste_requests USING GIST (geo)');
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_requests');
    }
};
