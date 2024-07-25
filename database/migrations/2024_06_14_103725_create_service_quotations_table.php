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
        Schema::create('service_quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('cus_id')->constrained('customers');
            $table->string('quotation_reference');
            $table->string('gst')->nullable();
            $table->date('expiration')->nullable();
            $table->string('pricelist')->nullable();
            $table->string('payment_terms')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('tax_amt', 10, 2)->nullable();
            $table->decimal('untax_amt', 10, 2)->nullable();
            $table->foreignId('sales_person')->nullable()->constrained('users');
            $table->string('sales_team')->nullable();
            $table->boolean('online_signature')->default(false);
            $table->boolean('online_payment')->default(false);
            $table->string('customer_reference')->nullable();
            $table->string('tags')->nullable();
            $table->string('fiscal_position')->nullable();
            $table->string('incoterm')->nullable();
            $table->string('incoterm_location')->nullable();
            $table->string('shipping_policy')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->string('source_document')->nullable();
            $table->string('campaign')->nullable();
            $table->string('medium')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_quotations');
    }
};
