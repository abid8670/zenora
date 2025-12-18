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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('office_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('serial_number')->unique()->nullable();
            $table->string('imei')->unique()->nullable();
            $table->decimal('purchase_cost', 10, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->enum('status', ['In Stock', 'Assigned', 'Damaged', 'Lost', 'Retired'])->default('In Stock');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
