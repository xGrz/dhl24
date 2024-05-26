<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use xGrz\Dhl24\Enums\DHLAddressType;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dhl_shipments', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable()->unique();
            $table->date('shipment_date')->nullable();
            $table->string('shipper_name', 60)->nullable();
            $table->string('shipper_postal_code', 10)->nullable();
            $table->string('shipper_city', 17)->nullable();
            $table->string('shipper_street', 35)->nullable();
            $table->string('shipper_house_number', 10)->nullable();
            $table->string('shipper_contact_person', 60)->nullable();
            $table->string('shipper_contact_phone', 20)->nullable();
            $table->string('shipper_contact_email', 60)->nullable();
            $table->string('receiver_type', 1)->default(DHLAddressType::CONSUMER);
            $table->string('receiver_country', 60)->default('PL');
            $table->boolean('is_packstation')->default(false);
            $table->boolean('is_postfiliale')->default(false);
            $table->string('receiver_name', 60)->nullable();
            $table->string('receiver_postal_code', 10)->nullable();
            $table->string('receiver_city', 17)->nullable();
            $table->string('receiver_street', 35)->nullable();
            $table->string('receiver_house_number', 10)->nullable();
            $table->string('receiver_contact_person', 60)->nullable();
            $table->string('receiver_contact_phone', 20)->nullable();
            $table->string('receiver_contact_email', 60)->nullable();
            $table->string('product', 2)->nullable();
            $table->boolean('delivery_evening')->default(false);
            $table->boolean('delivery_on_saturday')->default(false);
            $table->boolean('pickup_on_saturday')->default(false);
            $table->double('collect_on_delivery')->nullable();
            $table->string('collect_on_delivery_reference', 20)->nullable();
            $table->double('insurance')->nullable();
            $table->boolean('return_on_delivery')->default(false);
            $table->string('return_on_delivery_reference')->nullable();
            $table->boolean('proof_of_delivery')->default(false);
            $table->boolean('self_collect')->default(false);
            $table->boolean('predelivery_information')->default(false);
            $table->boolean('preaviso')->default(false);
            $table->string('payer_type')->nullable();
            $table->string('content', 30)->nullable();
            $table->string('comment', 100)->nullable();
            $table->string('reference', 200)->nullable();

            $table->double('cost')->nullable();
            $table->string('label', 30)->nullable();
            $table->foreignId('cost_center_id')->nullable()->references('id')->on('dhl_cost_centers')->nullOnDelete();
            $table->foreignId('courier_booking_id')->nullable()->references('id')->on('dhl_courier_bookings')->nullOnDelete();
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
