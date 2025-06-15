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
        Schema::create('bom', function (Blueprint $table) {
            $table->string('level', 4);
            $table->string('item_id', 16);
            $table->string('component_id', 16);
            $table->decimal('bom_multiplier', 20, 6);
            $table->primary(['item_id', 'component_id']);
            $table->foreign('item_id')->references('item_id')->on('item')->onDelete('cascade');
            $table->foreign('component_id')->references('item_id')->on('item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom');
    }
};
