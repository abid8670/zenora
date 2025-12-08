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
        // Drop the pivot table first if it exists
        Schema::dropIfExists('access_point_wifi_ssid');

        // Add the foreign key to the wifi_ssids table
        Schema::table('wifi_ssids', function (Blueprint $table) {
            $table->foreignId('access_point_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the pivot table on rollback
        Schema::create('access_point_wifi_ssid', function (Blueprint $table) {
            $table->id();
            $table->foreignId('access_point_id')->constrained()->onDelete('cascade');
            $table->foreignId('wifi_ssid_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Drop the foreign key from the wifi_ssids table on rollback
        Schema::table('wifi_ssids', function (Blueprint $table) {
            $table->dropForeign(['access_point_id']);
            $table->dropColumn('access_point_id');
        });
    }
};
