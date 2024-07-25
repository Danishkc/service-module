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
        Schema::create('service_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('quotation_id')->constrained('service_quotations');
            $table->foreignId('cus_id')->constrained('customers');
            $table->string('invoice_reference');
            $table->string('place_of_supply')->nullable();
            $table->string('gst')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('payment_reference')->nullable();
            $table->date('due_date')->nullable();
            $table->string('payment_terms')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('tax_amt', 10, 2)->nullable();
            $table->decimal('untax_amt', 10, 2)->nullable();
            $table->string('customer_reference')->nullable();
            $table->foreignId('sales_person')->nullable()->constrained('users');
            $table->string('sales_team')->nullable();
            $table->string('recipient_bank')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('incoterm')->nullable();
            $table->string('incoterm_location')->nullable();
            $table->string('fiscal_position')->nullable();
            $table->string('cash_round_method')->nullable();
            $table->string('auto_post')->nullable();
            $table->boolean('to_check')->default(false);
            $table->string('journal')->nullable();
            $table->string('pay_method')->nullable();
            $table->decimal('pay_amt', 10, 2)->nullable();
            $table->date('pay_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_invoices');
    }
};
