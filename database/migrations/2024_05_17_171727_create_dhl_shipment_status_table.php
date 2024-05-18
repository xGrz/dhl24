<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dhl_shipment_tracking', function (Blueprint $table) {
            $table
                ->foreignId('shipment_id')
                ->references('id')
                ->on('dhl_shipments')
                ->onDelete('CASCADE');
            $table->string('status', 10);
            $table->string('terminal', 20);
            $table->dateTime('event_timestamp');
        });

        Schema::table('dhl_shipment_tracking', function(Blueprint $table) {
            $table
                ->foreign('status')
                ->references('symbol')
                ->on('dhl_statuses');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dhl_shipment_tracking');
    }
};
