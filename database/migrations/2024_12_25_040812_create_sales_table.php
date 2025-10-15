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
            $table->integer("member_id")->nullable();
            $table->integer("total_item")->nullable();
            $table->integer("total_price")->nullable();
            $table->integer("discount")->default(0)->nullable();
            $table->integer("pay")->default(0)->nullable();
            $table->integer("accepted")->default(0)->nullable();
            $table->integer("user_id")->nullable();
            $table->json('product_ids')->nullable(); // store product IDs as JSON
            $table->json('return_products')->nullable();
            $table->enum('status', ['draft', 'complete', 'approved'])->default('draft')->nullable();
            $table->timestamps();
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
