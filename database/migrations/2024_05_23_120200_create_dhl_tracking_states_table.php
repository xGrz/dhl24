<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use xGrz\Dhl24\Helpers\DHLStatusSetup;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dhl_tracking_states', function (Blueprint $table) {
            $table->string('code', 10)->primary();
            $table->string('system_description');
            $table->string('description')->nullable();
            $table->integer('type')->nullable();
            $table->timestamps();
        });

        (new DHLStatusSetup())->run();
    }

    public function down(): void
    {
        Schema::dropIfExists('dhl_statuses');
    }
};
