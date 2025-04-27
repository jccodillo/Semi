<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique(); // e.g., REQ-2024-0001
            $table->string('control_number')->nullable();
            $table->string('product_name');
            $table->string('department');
            $table->string('branch')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->string('category');
            $table->string('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable(); // For admin comments/rejection reasons
            $table->foreignId('user_id')->constrained(); // Who requested it
            $table->foreignId('approved_by')->nullable()->constrained('users'); 
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('requests');
    }
};