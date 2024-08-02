<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes()->index();

            $table->string('vehicle_make', 30)->index();
            $table->string('vehicle_model', 30)->index();
            $table->integer('vehicle_kms')->unsigned();
            $table->integer('vehicle_price')->unsigned();
            $table->string('vehicle_plate', 10)->index();
            $table->string('notes', 300)->default('')->fulltext();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
