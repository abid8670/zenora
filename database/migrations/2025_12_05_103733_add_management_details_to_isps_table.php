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
            // Add fields for ISP Portal Credentials
            $table->string('management_url')->nullable()->after('status');
            $table->string('username')->nullable()->after('management_url');
            $table->string('password')->nullable()->after('username');

            // Add foreign key for Office
            $table->foreignId('office_id')->nullable()->constrained()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('isps', function (Blueprint $table) {
            $table->dropColumn(['management_url', 'username', 'password']);
            $table->dropForeign(['office_id']);
            $table->dropColumn('office_id');
        });
    }
};
