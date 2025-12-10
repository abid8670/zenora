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
        Schema::table('isps', function (Blueprint $table) {
            // Change static_ip to JSON to store key-value pairs and allow it to be nullable
            $table->json('static_ip')->nullable()->change();
            // Add a new nullable field for the firewall IP after static_ip
            $table->string('firewall_ip')->nullable()->after('static_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('isps', function (Blueprint $table) {
            // Revert static_ip back to a string
            $table->string('static_ip')->nullable()->change();
            // Drop the firewall_ip column
            $table->dropColumn('firewall_ip');
        });
    }
};
