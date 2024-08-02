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
        Schema::create('tasks', function(Blueprint $table) {
            $table->id();

            $table->tinyInteger('group_num')->unsigned()->default(1)->index();

            $table->string('title', 250)->default('');
            $table->string('description', 1000)->default('');

            $table->smallInteger('ordering', false, true)->default(1)->index();
            $table->tinyInteger('active', false, true)->default(1)->index();

            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('tasks');
    }
};
