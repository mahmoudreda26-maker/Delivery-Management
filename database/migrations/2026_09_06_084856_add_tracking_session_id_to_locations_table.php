<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->foreignId('tracking_session_id')
                ->nullable()
                ->after('vehicle_id')
                ->constrained('tracking_sessions')
                ->nullOnDelete();

            $table->index('tracking_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['tracking_session_id']);
            $table->dropIndex(['tracking_session_id']);
            $table->dropColumn('tracking_session_id');
        });
    }
};