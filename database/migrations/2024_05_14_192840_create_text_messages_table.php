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
        Schema::create('text_messages', function(Blueprint $table) {
            $table->id();
            $table->string('message_title', 50)->default('')->index();
            $table->string('message_notes', 150)->default('')->index();
            $table->string('message_content', 2000)->default('')->fulltext();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('text_messages');
    }
};
