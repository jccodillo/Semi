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
        Schema::create('stock_request_items', function (Blueprint $table) {
            $table->id();
            $table->string('stock_request_id');
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('category');
            $table->timestamps();
            
            // Remove the inline foreign key constraint
        });

        // Add the foreign key constraint after table creation
        // Only if the 'requests' table exists
        if (Schema::hasTable('requests')) {
            Schema::table('stock_request_items', function (Blueprint $table) {
                $table->foreign('stock_request_id')
                      ->references('request_id')
                      ->on('requests')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key before dropping the table
        if (Schema::hasTable('stock_request_items')) {
            Schema::table('stock_request_items', function (Blueprint $table) {
                // Drop foreign key - make sure to use consistent naming
                $table->dropForeign(['stock_request_id']);
            });
        }
        
        Schema::dropIfExists('stock_request_items');
    }
};
