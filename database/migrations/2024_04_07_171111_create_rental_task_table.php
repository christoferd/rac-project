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
        Schema::disableForeignKeyConstraints();

        // Create many to many relationship table between: `rentals` & `tasks`
        // https://laravel.com/docs/11.x/eloquent-relationships#many-to-many
        Schema::create('rental_task', function(Blueprint $table) {
            // DO NOT ADD INDEX to the first one. Otherwise Laravel will cry!
            $table->foreignId('rental_id')->references('id')->on('rentals');
            $table->foreignId('task_id')->references('id')->on('tasks')->index();
            $table->tinyInteger('completed')->default(0)->index();
            $table->integer('user_completed')->default(0)->index();
            $table->timestamp('datetime_completed')->nullable()->default(null)->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('rental_task');
    }
};
