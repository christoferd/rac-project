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
        Schema::create('clients', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes()->index();

            $table->string('name', 80)->default('')->index();
            $table->string('address', 100)->default('')->index();
            $table->string('phone_number', 50)->default('')->index();
            $table->string('notes', 1000)->default('')->fulltext();
            $table->tinyInteger('rating')->default('-1')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
