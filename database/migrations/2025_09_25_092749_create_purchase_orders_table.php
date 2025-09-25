<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->string('purchase_company'); // company name
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->decimal('rate', 10, 2); // Rs
            $table->decimal('total', 12, 2); // auto-calculated: quantity * rate
            $table->date('issue_date');
            $table->time('issue_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos');
    }
};
