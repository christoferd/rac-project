<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('vehicles', function(Blueprint $table) {
            $table->smallInteger('ordering')->default(100)->unsigned()->index();
        });
        $qry = 'UPDATE `vehicles` SET `ordering` = `id` WHERE 1';
        \Illuminate\Support\Facades\DB::statement($qry);
    }

    public function down(): void
    {
        Schema::table('vehicles', function(Blueprint $table) {
            $table->dropColumn('ordering');
        });
    }
};
