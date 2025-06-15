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
        Schema::create('item', function (Blueprint $table) {
            $table->string('item_id', 16)->primary();
            $table->string('item_name', 96)->nullable();
            $table->integer('lot_size');
            $table->integer('lead_time');
            $table->decimal('inventory');
            $table->integer('level');
            $table->string('satuan')->nullable();
            $table->enum('tipe', ['statis', 'dinamis'])->default('statis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
