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
        Schema::create('supplies_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('control_code')->unique();
            $table->string('product_name');
            $table->integer('quantity')->default(0);
            $table->string('unit_type'); // e.g., pieces, boxes, reams, etc.
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies_inventory');
    }
};