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
            $table->id();
            $table->unsignedBigInteger('grn_id');
            $table->unsignedBigInteger('product_id');
            $table->string('uom', 50)->nullable(); // Unit of Measurement
            $table->integer('qty_ordered')->default(0);
            $table->integer('qty_received')->default(0);
            $table->integer('qty_accepted')->default(0);
            $table->integer('qty_rejected')->default(0);
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['draft', 'complete', 'pending', 'reject'])->default('draft');
            $table->timestamps();

            // Foreign keys
            $table->foreign('grn_id')->references('id')->on('grns')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
