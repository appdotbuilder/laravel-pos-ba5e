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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->comment('Stock Keeping Unit');
            $table->string('name')->comment('Product name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('harga_beli', 15, 2)->comment('Purchase price');
            $table->decimal('harga_jual', 15, 2)->comment('Selling price');
            $table->integer('stok')->default(0)->comment('Stock quantity');
            $table->string('barcode')->nullable()->comment('Product barcode');
            $table->text('description')->nullable()->comment('Product description');
            $table->boolean('is_active')->default(true)->comment('Product status');
            $table->timestamps();
            
            $table->index('sku');
            $table->index('name');
            $table->index(['category_id', 'is_active']);
            $table->index('barcode');
            $table->index(['is_active', 'stok']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};