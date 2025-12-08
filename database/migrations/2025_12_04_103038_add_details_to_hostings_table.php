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
        Schema::table('hostings', function (Blueprint $table) {
            // Adding new columns after 'password' column for better organization
            $table->date('registration_date')->nullable()->after('password');
            $table->date('expiry_date')->nullable()->after('registration_date');
            $table->json('nameservers')->nullable()->after('expiry_date');
            $table->string('dns_management_url')->nullable()->after('nameservers');
            $table->text('backup_info')->nullable()->after('dns_management_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hostings', function (Blueprint $table) {
            $table->dropColumn([
                'registration_date',
                'expiry_date',
                'nameservers',
                'dns_management_url',
                'backup_info'
            ]);
        });
    }
};
