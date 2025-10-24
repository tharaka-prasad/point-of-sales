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
        Schema::create('grn_items', function (Blueprint $table) {
            $table->id('grn_item_id');
            $table->foreignId('grn_id')->constrained('grns')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->string('description')->nullable();
            $table->string('uom')->nullable();
            $table->integer('qty_ordered')->default(0)->nullable();
            $table->integer('qty_received')->nullable();
            $table->integer('qty_accepted')->nullable();
            $table->integer('qty_rejected')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->string('remarks')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grn_items');
    }
};
