<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove Roles from Permissions
        // Permission::all()->each(function($permission) {
        //     $permission->roles()->detach();
        // });
        //
        // // Delete all Roles
        // Role::all()->each(function($role) {
        //     $role->forceDelete();
        // });
        //
        // // Remove Roles from Permissions
        // Permission::all()->each(function($permission) {
        //     $permission->roles()->detach();
        // });
        //
        // // Delete all Permissions
        // Permission::all()->each(function($permission) {
        //     $permission->forceDelete();
        // });

        /*
         * Create Roles
         */
        $roleDev = Role::findOrCreate('developer');
        $roleAdmin = Role::findOrCreate('admin');
        $roleStaff = Role::findOrCreate('staff');

        /*
         * Permissions
         * Dev
         */
        $guard = 'web';
        Permission::findOrCreate('access api.tokens', $guard)->syncRoles([$roleDev]);
        /*
         * Permissions - Create & Assign
         */
        Permission::findOrCreate('access tasks', $guard)->syncRoles([$roleAdmin]);
        Permission::findOrCreate('edit tasks', $guard)->syncRoles([$roleAdmin]);
        Permission::findOrCreate('edit vehicles', $guard)->syncRoles([$roleAdmin]);
        Permission::findOrCreate('delete records', $guard)->syncRoles([$roleAdmin]);

        Permission::findOrCreate('access dashboard', $guard)->syncRoles([$roleAdmin, $roleStaff]);
        Permission::findOrCreate('access clients', $guard)->syncRoles([$roleAdmin, $roleStaff]);
        Permission::findOrCreate('edit clients', $guard)->syncRoles([$roleAdmin, $roleStaff]);
        Permission::findOrCreate('access vehicles', $guard)->syncRoles([$roleAdmin, $roleStaff]);
        Permission::findOrCreate('access calendar', $guard)->syncRoles([$roleAdmin, $roleStaff]);
        Permission::findOrCreate('access rentals', $guard)->syncRoles([$roleAdmin, $roleStaff]);
        Permission::findOrCreate('edit rentals', $guard)->syncRoles([$roleAdmin, $roleStaff]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // $roleAdmin = Role::findByName('admin');
        // $roleAdmin->revokePermissionTo('access tasks');
        // $roleAdmin->revokePermissionTo('edit tasks');
    }
};
