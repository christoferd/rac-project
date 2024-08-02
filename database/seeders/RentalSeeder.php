<?php

namespace Database\Seeders;

use App\Library\DateLib;
use App\Models\Client;
use App\Models\Rental;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo '== RentalSeeder == '.\PHP_EOL;

        Schema::disableForeignKeyConstraints();

        DB::statement('TRUNCATE TABLE `rentals`;');
        echo '# Create Demo Rentals...'.\PHP_EOL;
        if(app()->isProduction()) {
            echo '--skip'.\PHP_EOL;
            return;
        }

        /**
         * @var Vehicle $vehicle
         */

        $nl = "\n";
        $vehicles = Vehicle::all();
        $clientsIdOnly = Client::select('id')->where('id', '>', 1)->get();
        $dateEndLoop = Carbon::today()->addDays(30);
        $possibleTimeSlots = collect(config('rental.calendar_entry_time_slots'))->pluck('value');
        $minDaysRent = 1;
        $maxDaysRent = 7;
        $iReturnFrom = 0;
        $iReturnTo = intval(floor($possibleTimeSlots->count() / 2));
        $iCollectFrom = $iReturnTo + 1;
        $iCollectTo = $possibleTimeSlots->count() - 1;

        echo "$iCollectFrom, $iCollectTo, $iReturnFrom, $iReturnTo $nl";

        // Loop cars
        foreach($vehicles as $vehicle) {
            echo "{$vehicle->id}: {$vehicle->vehicle_make}  {$vehicle->vehicle_model} $nl";
            // First Rental
            $cCollect = Carbon::today()->subDays(rand(50, 60));
            $cCollect->setTimeFromTimeString($possibleTimeSlots[rand($iCollectFrom, $iCollectTo)]);
            $cReturn = clone($cCollect);
            $cReturn->addDays(rand(0, 3));
            $cReturn->setTimeFromTimeString($possibleTimeSlots[rand($iReturnFrom, $iReturnTo)]);

            // Loop
            while($cReturn->lt($dateEndLoop)) {

                $daysToCharge = DateLib::diffInDaysOfDateOnly($cCollect, $cReturn);

                $data = [
                    'date_collect'   => $cCollect->toDateString(),
                    'time_collect'   => $cCollect->toTimeString(),
                    'date_return'    => $cReturn->toDateString(),
                    'time_return'    => $cReturn->toTimeString(),
                    'client_id'      => $clientsIdOnly->random()->id,
                    'vehicle_id'     => $vehicle->id,
                    'price_day'      => $vehicle->vehicle_price,
                    'days_to_charge' => $daysToCharge,
                    'price_total'    => ($vehicle->vehicle_price * $daysToCharge),
                    'notes'          => '',
                ];

                // echo 'Data: '.implode(',', $data).$nl;

                try {
                    Rental::create($data);
                }
                catch(\Throwable $ex) {
                    dump($data);
                    throw $ex;
                }

                $cCollect = $cReturn->copy();
                $r = rand(0, 2);
                $cCollect->addDays($r);
                if($r === 0) {
                    $cCollect->setTimeFromTimeString($possibleTimeSlots[rand($iCollectFrom, $iCollectTo)]);
                }
                else {
                    // Different day, can collect at any time
                    $cCollect->setTimeFromTimeString($possibleTimeSlots[rand(0, $iCollectTo)]);
                }
                $cReturn = $cCollect->copy();
                $cReturn->addDays(rand(1, 7));
                $cReturn->setTimeFromTimeString($possibleTimeSlots[rand($iReturnFrom, $iReturnTo)]);
            }
        }
    }
}
