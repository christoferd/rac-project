<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo '== VehicleSeeder == '.\PHP_EOL;

        DB::statement('TRUNCATE TABLE `vehicles`;');
        echo '# Create Vehicles...'.\PHP_EOL;

        $vehicles = [
            'Hyundai,hb20 sed,0,2600,DAB8717',
            'Onix,Nuevo gris,0,2500,DAB9579',
            'Onix,Nuevo rojo,0,2500,DAB9578',
            'Onix,Joy 3,0,2200,DAB5546',
            'Onix,Joy 4,0,2000,DAB4314',
            'Chevrolet,onix 5,0,2200,DAB8146',
            'Suzuki,Swift,0,1800,SBV4076',
            'Nissan,March,0,2200,DVE0779',
            'VW,Gol nuevo,0,2400,DAB7240',
            'Fiat,strada SIMPLE,0,2300,DAB7386',
            'Fiat,strada DOBLE,0,2500,DAB7919',
            'Fiat,Strada DOBLE,0,2500,DAB8301',
            'Fiat,Doble 3,0,2000,DAB5936',
            'Renault,Oroch,0,2500,DAB6867',
            'Renault,Oroch roja,0,2650,DAB7094',
            'Peugeot,Partner,0,2500,DAB2844',
            'Renault,Oroch Nuestra,0,2000,DAB0000',
        ];

        foreach($vehicles as $i => $vehicleDataStr) {
            list($make, $model, $kms, $price, $plate) = explode(',', $vehicleDataStr);
            Vehicle::create([
                                'vehicle_make'   => $make,
                                'vehicle_model'  => $model,
                                'vehicle_kms'    => $kms,
                                'vehicle_price'  => $price,
                                'vehicle_plate'  => $plate,
                                'active' => '1',
                                'ordering' => ($i+1)
                            ]);
        }
    }
}
