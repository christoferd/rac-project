<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roleDev = Role::findOrCreate('developer');
        $roleAdmin = Role::findOrCreate('admin');
        $roleStaff = Role::findOrCreate('staff');

        $guard = 'web';
        Permission::findOrCreate('access messages', $guard)->syncRoles([$roleDev, $roleAdmin, $roleStaff]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::find('access messages')->syncRoles([])->delete();
    }
};
