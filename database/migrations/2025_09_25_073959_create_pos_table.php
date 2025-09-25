<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique(); // PO-1000 format
            $table->unsignedBigInteger('supplier_id'); // company from Supplier
            $table->unsignedBigInteger('product_id');  // item code from Product
            $table->integer('quantity');
            $table->decimal('rate', 10, 2);
            $table->date('issue_date');
            $table->time('issue_time');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
