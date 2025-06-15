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
        Schema::create('order', function (Blueprint $table) {
            $table->string('order_id', 16)->primary();
            $table->string('customer_id', 16)->nullable();
            $table->integer('period_number');
            $table->string('item_id', 16);
            $table->integer('quantity');
            $table->datetime('order_date')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customer')->onDelete('set null');
            $table->foreign('period_number')->references('period_number')->on('period');
            $table->foreign('item_id')->references('item_id')->on('item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
