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
        Schema::create('service_quotation_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('service_quotations')->onDelete('cascade');
            $table->foreignId('pro_id')->nullable()->constrained('products');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->json('taxes')->nullable();
            $table->text('section')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('option_pro_id')->nullable()->constrained('products');
            $table->text('option_description')->nullable();
            $table->decimal('option_quantity', 10, 2)->nullable();
            $table->decimal('option_unit_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_quotation_lines');
    }
};
