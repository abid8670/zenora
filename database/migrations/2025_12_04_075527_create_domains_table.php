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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->string('registrar');
            $table->date('registration_date');
            $table->date('expiry_date');
            $table->text('name_servers')->nullable(); // For comma-separated NS records
            $table->text('dns_details')->nullable(); // For other DNS records
            
            // Foreign keys for hostings. 'set null' means if a hosting is deleted, this field becomes null.
            $table->foreignId('primary_hosting_id')->nullable()->constrained('hostings')->onDelete('set null');
            $table->foreignId('backup_hosting_id')->nullable()->constrained('hostings')->onDelete('set null');

            $table->foreignId('office_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
