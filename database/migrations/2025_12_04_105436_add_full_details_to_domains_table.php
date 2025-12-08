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
        Schema::table('domains', function (Blueprint $table) {
            $table->string('owner_name')->nullable()->after('expiry_date');
            $table->string('owner_email')->nullable()->after('owner_name');
            $table->string('panel_url')->nullable()->after('owner_email');
            $table->string('panel_username')->nullable()->after('panel_url');
            $table->text('panel_password')->nullable()->after('panel_username');
            $table->json('nameservers')->nullable()->after('panel_password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn(['owner_name', 'owner_email', 'panel_url', 'panel_username', 'panel_password', 'nameservers']);
        });
    }
};
