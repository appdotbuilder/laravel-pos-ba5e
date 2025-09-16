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
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('file_name')->comment('Name of imported file');
            $table->integer('total_rows')->comment('Total rows in file');
            $table->integer('successful_rows')->comment('Successfully imported rows');
            $table->integer('updated_rows')->comment('Updated existing rows');
            $table->integer('failed_rows')->comment('Failed rows');
            $table->json('errors')->nullable()->comment('Import errors and details');
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};