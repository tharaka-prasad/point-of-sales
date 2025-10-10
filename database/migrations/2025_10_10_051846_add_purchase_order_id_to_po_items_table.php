<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('po_items', function (Blueprint $table) {
        $table->unsignedBigInteger('purchase_order_id')->after('id');

        $table->foreign('purchase_order_id')
            ->references('id')
            ->on('purchase_orders')
            ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('po_items', function (Blueprint $table) {
            $table->dropForeign(['purchase_order_id']);
            $table->dropColumn('purchase_order_id');
        });
    }
};
