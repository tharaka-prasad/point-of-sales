<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    // Run the migrations.
    public function up(): void
    {
        Schema::create('kitchen', function (Blueprint $table) {
            $table->id();
            $table->integer('item_name')->nullable();
            $table->integer('qty')->nullable();
            $table->string('unit')->nullable(); 
            $table->date('issue_date')->nullable();
            $table->string('issued_by')->nullable();
            $table->enum('meal_type' , ['breakfast', 'lunch', 'dinner', 'all'])->nullable();
            $table->timestamps();
        });
    }


    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('kitchen');
    }
};
