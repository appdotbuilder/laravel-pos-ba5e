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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->comment('Quantity sold');
            $table->decimal('unit_price', 15, 2)->comment('Price per unit at time of sale');
            $table->decimal('discount_amount', 15, 2)->default(0)->comment('Discount amount for this item');
            $table->decimal('total_price', 15, 2)->comment('Total price for this line item');
            $table->timestamps();
            
            $table->index(['sale_id', 'product_id']);
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};