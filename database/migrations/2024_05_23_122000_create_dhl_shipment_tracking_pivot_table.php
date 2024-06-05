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
            $table->string('code_id', 10);
            $table->string('terminal', 20)->nullable();
            $table->dateTime('event_timestamp');

            $table
                ->foreign('code_id')
                ->references('code')
                ->on('dhl_tracking_states');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dhl_shipment_tracking');
    }
};
