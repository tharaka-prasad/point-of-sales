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
            $table->text('supplier_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('rate', 10, 2)->nullable(); // Rs
            $table->enum('status', ['draft', 'complete', 'pending', 'reject'])->default('draft');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos');
    }
};
