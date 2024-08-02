<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo '== UserSeeder == '.\PHP_EOL;
        echo '== !!! Also See Migrations !!! == '.\PHP_EOL;

        $c = Carbon::now();
        $dt = $c->toDateTimeString();
        $user = User::factory()->create([
                                                'name'              => 'RentACar-Dev',
                                                'email'             => 'dev@rentacar.dedavid.net',
                                                'password'          => \bcrypt('Testing999###'),
                                                'email_verified_at' => $dt,
                                                'updated_at'        => $dt,
                                                'created_at'        => $dt,
                                            ]);
        $user->assignRole(['developer','admin']);
        echo '* Created User: '.$user->name.' | '.$user->email.\PHP_EOL;

        $c = Carbon::now();
        $dt = $c->toDateTimeString();
        $user = User::factory()->create([
                                                'name'              => 'RentACar-Admin',
                                                'email'             => 'admin@rentacar.dedavid.net',
                                                'password'          => \bcrypt('Testing999###'),
                                                'email_verified_at' => $dt,
                                                'updated_at'        => $dt,
                                                'created_at'        => $dt,
                                            ]);
        $user->assignRole(['admin']);
        echo '* Created User: '.$user->name.' | '.$user->email.\PHP_EOL;

        $c = Carbon::now();
        $dt = $c->toDateTimeString();
        $user = User::factory()->create([
                                                'name'              => 'RentACar-Staff',
                                                'email'             => 'staff@rentacar.dedavid.net',
                                                'password'          => \bcrypt('Testing999###'),
                                                'email_verified_at' => $dt,
                                                'updated_at'        => $dt,
                                                'created_at'        => $dt,
                                            ]);
        $user->assignRole(['staff']);
        echo '* Created User: '.$user->name.' | '.$user->email.\PHP_EOL;
    }
}
