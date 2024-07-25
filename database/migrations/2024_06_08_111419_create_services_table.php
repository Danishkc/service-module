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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->boolean('priority')->default(0);
            $table->string('repair_reference')->unique();
            $table->foreignId("cus_id")->constrained('customers')->onDelete('cascade');
            $table->foreignId("pro_id")->nullable()->constrained('products')->onDelete('cascade');
            $table->integer('status')->default(0); 
            $table->foreignId("user_id")->constrained('users')->onDelete('cascade');
            $table->decimal('product_quantity', 10, 2)->nullable();
            $table->string("return")->nullable();
            $table->boolean('warranty')->default(false); 
            $table->dateTime('scheduled_date'); 
            $table->foreignId("responsible")->nullable()->constrained('users')->onDelete('cascade');
            $table->string('tags')->nullable();
            $table->boolean('component_status')->default(true);
            $table->text('repair_notes')->nullable();
            $table->string('operation_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
