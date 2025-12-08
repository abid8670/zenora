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
        Schema::create('isps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider');
            $table->string('speed');
            $table->string('circuit_id')->nullable();
            $table->string('connection_type')->nullable();
            $table->string('location')->nullable();
            $table->ipAddress('static_ip')->nullable()->unique();
            $table->string('status')->default('Active');
            $table->string('account_number')->nullable();
            $table->decimal('monthly_cost', 8, 2)->nullable();
            $table->date('billing_date')->nullable();
            $table->date('installation_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('isps');
    }
};
