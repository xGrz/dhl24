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
        Schema::create('dhl_courier_bookings', function (Blueprint $table) {
            $table->id();
            $table->date('pickup_date');
            $table->time('pickup_from');
            $table->time('pickup_to');
            $table->string('additional_info', 50)->nullable();
            $table->string('orderId')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dhl_courier_bookings');
    }
};
