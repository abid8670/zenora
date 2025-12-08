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
        Schema::create('hostings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Unique Nickname for the hosting
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->string('provider');
            $table->string('plan_name')->nullable();
            $table->ipAddress('server_ip')->nullable();
            $table->string('login_url')->nullable();
            $table->string('username')->nullable();
            $table->text('password')->nullable(); // Encrypted password
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
        Schema::dropIfExists('hostings');
    }
};
