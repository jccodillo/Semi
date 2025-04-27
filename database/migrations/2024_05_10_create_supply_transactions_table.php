<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_id')->constrained('supplies_inventory')->onDelete('cascade');
            $table->enum('transaction_type', ['receipt', 'issuance']);
            $table->integer('quantity');
            $table->string('reference_number');
            $table->integer('balance');
            $table->string('office')->nullable();
            $table->integer('days_to_consume')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supply_transactions');
    }
}; 