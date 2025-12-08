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
        Schema::create('p2p_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link_type'); // P2P Radio Link, Site-to-Site VPN, etc.
            $table->string('status')->default('Active');
            
            $table->foreignId('office_a_id')->constrained('offices');
            $table->foreignId('office_b_id')->constrained('offices');

            // For Radio Link Device or VPN Server Management
            $table->string('device_url')->nullable();
            $table->string('username')->nullable();
            $table->text('password')->nullable(); // Using text to store encrypted value

            // VPN-specific credentials
            $table->ipAddress('vpn_server_ip')->nullable();
            $table->string('vpn_user')->nullable();
            $table->text('vpn_password')->nullable(); // Using text to store encrypted value

            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p2p_links');
    }
};
