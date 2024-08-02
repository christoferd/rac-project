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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes()->index();

            $table->date('date_collect')->index();
            $table->time('time_collect')->index();
            $table->date('date_return')->index();
            $table->time('time_return');
            $table->integer('client_id')->index();
            $table->integer('vehicle_id')->index();
            $table->integer('price_day');
            $table->integer('days_to_charge');
            $table->integer('price_total');
            $table->string('notes', 300)->default('')->fulltext();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
