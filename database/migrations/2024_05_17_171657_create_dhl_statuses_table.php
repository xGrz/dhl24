<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use xGrz\Dhl24\Enums\StatusType;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dhl_statuses', function (Blueprint $table) {
            $table->string('symbol', 10)->primary();
            $table->string('description');
            $table->string('custom_description')->nullable();
            $table->integer('type')->default(StatusType::NEW);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dhl_statuses');
    }
};
