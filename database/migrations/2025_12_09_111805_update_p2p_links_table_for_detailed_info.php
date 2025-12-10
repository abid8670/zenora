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
        Schema::table('p2p_links', function (Blueprint $table) {
            // Add new detailed columns
            $table->string('link_speed')->nullable()->after('status');
            $table->string('ownership')->nullable()->after('link_speed'); // e.g., Owned, Rented
            
            // Device A Details
            $table->string('device_a_type')->nullable()->after('office_b_id');
            $table->string('device_a_mode')->nullable()->after('device_a_type'); // e.g., AP, Station, Bridge
            $table->string('device_a_wan_ip')->nullable()->after('device_a_mode');
            $table->string('device_a_url')->nullable()->after('device_a_wan_ip');
            $table->string('device_a_username')->nullable()->after('device_a_url');
            $table->text('device_a_password')->nullable()->after('device_a_username');

            // Device B Details
            $table->string('device_b_type')->nullable()->after('device_a_password');
            $table->string('device_b_mode')->nullable()->after('device_b_type');
            $table->string('device_b_wan_ip')->nullable()->after('device_b_mode');
            $table->string('device_b_url')->nullable()->after('device_b_wan_ip');
            $table->string('device_b_username')->nullable()->after('device_b_url');
            $table->text('device_b_password')->nullable()->after('device_b_username');

            // Drop old generic columns
            $table->dropColumn([
                'device_url',
                'username',
                'password',
                'vpn_server_ip',
                'vpn_user',
                'vpn_password',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p2p_links', function (Blueprint $table) {
            // Add back the old columns
            $table->string('device_url')->nullable();
            $table->string('username')->nullable();
            $table->text('password')->nullable();
            $table->string('vpn_server_ip')->nullable();
            $table->string('vpn_user')->nullable();
            $table->text('vpn_password')->nullable();

            // Drop the new detailed columns
            $table->dropColumn([
                'link_speed',
                'ownership',
                'device_a_type',
                'device_a_mode',
                'device_a_wan_ip',
                'device_a_url',
                'device_a_username',
                'device_a_password',
                'device_b_type',
                'device_b_mode',
                'device_b_wan_ip',
                'device_b_url',
                'device_b_username',
                'device_b_password',
            ]);
        });
    }
};
