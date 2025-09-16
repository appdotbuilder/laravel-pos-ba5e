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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Customer name');
            $table->string('email')->nullable()->comment('Customer email');
            $table->string('phone')->nullable()->comment('Customer phone number');
            $table->text('address')->nullable()->comment('Customer address');
            $table->decimal('total_spent', 15, 2)->default(0)->comment('Total amount spent');
            $table->integer('total_orders')->default(0)->comment('Total number of orders');
            $table->timestamp('last_order_at')->nullable()->comment('Last order date');
            $table->timestamps();
            
            $table->index('name');
            $table->index('email');
            $table->index('phone');
            $table->index('total_spent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};