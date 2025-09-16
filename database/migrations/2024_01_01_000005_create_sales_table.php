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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique()->comment('Sales invoice number');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained();
            $table->decimal('subtotal', 15, 2)->comment('Subtotal before discount');
            $table->decimal('discount_amount', 15, 2)->default(0)->comment('Total discount amount');
            $table->decimal('tax_amount', 15, 2)->default(0)->comment('Tax amount');
            $table->decimal('total_amount', 15, 2)->comment('Final total amount');
            $table->enum('payment_method', ['cash', 'card', 'digital'])->comment('Payment method');
            $table->decimal('paid_amount', 15, 2)->comment('Amount paid by customer');
            $table->decimal('change_amount', 15, 2)->default(0)->comment('Change given to customer');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamps();
            
            $table->index('invoice_number');
            $table->index(['user_id', 'created_at']);
            $table->index(['customer_id', 'created_at']);
            $table->index(['created_at', 'total_amount']);
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};