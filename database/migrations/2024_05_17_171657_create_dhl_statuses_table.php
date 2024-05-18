<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dhl_statuses', function (Blueprint $table) {
            $table->string('symbol')->primary();
            $table->string('description');
            $table->string('custom_description')->nullable();
            // TODO: type
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dhl_statuses');
    }
};
