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
        Schema::create('dhl_shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id')->nullable()->unique();
            $table->json('shipper')->nullable();
            $table->json('receiver')->nullable();
            $table->json('piece_list')->nullable();
            $table->json('service')->nullable();
            $table->json('payment')->nullable();
            $table->date('shipment_date')->nullable();
            $table->string('content')->nullable();
            $table->double('cod')->nullable();
            $table->tinyInteger('items')->default(0);
            $table->double('cost')->nullable();
            $table->foreignId('courier_booking_id')->nullable()->references('id')->on('dhl_courier_bookings');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dhl_shipments');
    }
};
