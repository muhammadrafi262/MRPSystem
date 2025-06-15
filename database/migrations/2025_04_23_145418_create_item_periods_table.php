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
        Schema::create('item_period', function (Blueprint $table) {
            $table->string('item_id', 16);
            $table->integer('period_number');
            $table->float('gross_requirement', 20, 6)->nullable();
            $table->float('projected_inventory', 20, 6)->nullable();
            $table->float('planned_order_receipt', 20, 6)->nullable();
            $table->float('planned_order_release', 20, 6)->nullable();
            $table->primary(['item_id', 'period_number']);
            $table->timestamps();

            $table->foreign('item_id')->references('item_id')->on('item')->onDelete('cascade');
            $table->foreign('period_number')->references('period_number')->on('period')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_period');
    }
};
