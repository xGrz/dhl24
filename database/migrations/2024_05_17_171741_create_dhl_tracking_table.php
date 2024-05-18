<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dhl_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->references('id')->on('dhl_shipments')->onDelete('cascade');
            $table->string('status_symbol', 10);
            $table->string('terminal');
            $table->dateTime('event_at');
            $table->timestamps();
        });

        Schema::table('dhl_tracking', function (Blueprint $table) {
            $table->foreign('status_symbol')->references('symbol')->on('dhl_statuses');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dhl_tracking');
    }
};
