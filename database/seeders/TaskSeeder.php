<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo '== TaskSeeder == '.\PHP_EOL;

        $taskTitles = [
            'Video del vehículo.',
            'Recorrida con el cliente en el vehículo.',
            'Cierre de puertas.',
            'Mostrar gato, Llave de rueda y auxiliar.',
            'Mostrar libreta de y seguro del vehículo.',
            'Si ese vehículo tiene sexta explicarle.',
            'Recordar la precaución con los radares.',
            'Telepeaje integrado en el vehiculo.',
        ];

        Schema::disableForeignKeyConstraints();

        DB::statement('TRUNCATE TABLE `tasks`;');
        echo '# Create Rental Tasks...'.\PHP_EOL;

        foreach($taskTitles as $i => $taskTitle) {
            $t = new Task();
            $t->group_num = 1;
            $t->ordering = ($i + 1);
            $t->active = 1;
            $t->title = $taskTitle;
            $t->save();
        }
    }
}
