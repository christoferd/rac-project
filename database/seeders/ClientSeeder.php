<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo '# Create Demo Clients...'.\PHP_EOL;
        if(app()->isProduction()) {
            echo '--skip'.\PHP_EOL;
            return;
        }
        /*
         * Create a client to always be the default for this company
         */
        $client = Client::create(
            [
                'name' => 'Rent a Car',
                'address' => 'Montevideo, Uruguay',
                'phone_number' => '',
                'notes' => '',
                'rating' => -1,
            ]
        );
        if($client->id !== 1) {
            throw new \Exception('First Client ID should be 1');
        }

        echo '# Create Demo Clients...'.\PHP_EOL;
        Client::factory(50)->create();
    }
}
