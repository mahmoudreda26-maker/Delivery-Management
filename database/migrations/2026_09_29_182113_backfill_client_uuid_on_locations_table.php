<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill client_uuid on locations table
       DB::table('locations')
             ->whereNull('client_uuid')
             ->orderBy('id')
             ->chunkById(100, function ($locations){
                foreach ($locations as $location){
                    DB::table('locations')
                        ->where('id', $location->id)
                        ->update([
                            'client_uuid' => (string) Str::uuid(),
                        ]);
                }
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
