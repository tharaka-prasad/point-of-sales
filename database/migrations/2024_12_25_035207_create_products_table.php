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
            $table->string("code")->nullable();
            $table->string("name")->nullable();
            $table->integer("category_id")->nullable();
            $table->string("brand")->nullable();
            $table->integer("price")->nullable();
            $table->integer("discount")->default(0)->nullable();
            $table->integer("sell_price")->nullable();
            $table->integer("stock")->nullable();
            $table->date("expiry_date")->nullable();
            $table->timestamps();
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
