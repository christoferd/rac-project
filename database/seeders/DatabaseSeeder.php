<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo '== DatabaseSeeder =='.\PHP_EOL;
        echo 'Environment: '.app()->environment().\PHP_EOL;
        echo 'Config app.env: '.config('app.env').\PHP_EOL;

        if(app()->isProduction()) {
            echo '!!!!!!!!!!******************************************************'.\PHP_EOL;
            echo '   NOT ALLOWED class DatabaseSeeder in Production'.\PHP_EOL;
            echo '!!!!!!!!!!******************************************************'.\PHP_EOL;
        }
        else {
            // IMPORTANT to keep in this order
            $this->call(RolesSeeder::class);
            $this->call(PermissionsSeeder::class);
            $this->call(UserSeeder::class);

            $this->call(TaskSeeder::class);

            // Client Business Data
            // for initial install
            $this->call(VehicleSeeder::class);

            // Test/Demo Data
            $this->call(ClientSeeder::class);
            $this->call(RentalSeeder::class);
            $this->call(TextMessageSeeder::class);
        }
    }
}
