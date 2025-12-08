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
        Schema::create('access_point_wifi_ssid', function (Blueprint $table) {
            $table->id();
            $table->foreignId('access_point_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wifi_ssid_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_point_wifi_ssid');
    }
};
