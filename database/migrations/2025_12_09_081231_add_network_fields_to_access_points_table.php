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
        Schema::table('access_points', function (Blueprint $table) {
            $table->string('wan_ip')->nullable()->after('ip_address');
            $table->string('lan_ip')->nullable()->after('wan_ip');
            $table->string('mode')->nullable()->after('lan_ip');
            $table->string('type')->nullable()->after('mode');
            $table->string('vlan_id')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('access_points', function (Blueprint $table) {
            $table->dropColumn(['wan_ip', 'lan_ip', 'mode', 'type', 'vlan_id']);
        });
    }
};
