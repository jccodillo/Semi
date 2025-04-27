<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('control_number')->unique();
            $table->string('product_name');
            $table->string('department');
            $table->string('branch');
            $table->integer('quantity');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('category');
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};