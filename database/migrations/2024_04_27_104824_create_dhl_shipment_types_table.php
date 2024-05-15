<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dhl_shipment_types', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 20);
            $table->string('quantity',3)->default(1);
            $table->string('weight',4)->nullable();
            $table->string('width', 3)->nullable();
            $table->string('height', 3)->nullable();
            $table->string('length', 3)->nullable();
            $table->boolean('non_standard')->nullable()->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dhl_shipment_types');
    }
};
