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
        Schema::create('po_items', function (Blueprint $table) {
            $table->id('purchase_order_id');
            $table->string('item_name')->nullable();
            $table->string('category')->nullable();
            $table->string('uom')->nullable();
            $table->string('qty')->nullable();
            $table->decimal('rate', 10, 2)->nullable(); // Rs
            $table->string('remarks')->nullable();
            $table->decimal('total', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_items');
    }
};
