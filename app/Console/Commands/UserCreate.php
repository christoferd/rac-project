<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-create {name} {email} {password} {roles-csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing User matching the `name`, else Create a new User';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        // Update user if exists
        $user = User::where('name', $name)->first();
        if(!$user) {
            $user = new User();
            $user->name = $name;
            $this->info('New User Created...');
        }
        else {
            $this->info('Updating Existing User #'.$user->id.'...');
        }
        $this->info('- Name: '.$user->name);
        $user->email = $this->argument('email');
        $this->info('- Email: '.$user->email);
        $user->password = bcrypt($this->argument('password'));
        $user->save();

        $roles = explode(',', $this->argument('roles-csv'));
        foreach($roles as $role) {
            $this->info('- Assign Role: '.$role);
            $user->assignRole($role);
        }

        $this->info('== Process Complete ==');
    }
}
