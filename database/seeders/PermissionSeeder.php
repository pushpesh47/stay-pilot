<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['heading' => 'Role', 'name' => 'role.create', 'title' => 'Create', 'guard_name' => 'web'],
            ['heading' => 'Role', 'name' => 'role.view', 'title' => 'View', 'guard_name' => 'web'],
            ['heading' => 'Role', 'name' => 'role.edit', 'title' => 'Edit', 'guard_name' => 'web'],
            ['heading' => 'Role', 'name' => 'role.delete', 'title' => 'Delete', 'guard_name' => 'web'],

            ['heading' => 'Ra Document', 'name' => 'ra-document.view', 'title' => 'View', 'guard_name' => 'web'],
            ['heading' => 'Ra Document', 'name' => 'ra-document.create', 'title' => 'Create', 'guard_name' => 'web'],
            ['heading' => 'Ra Document', 'name' => 'ra-document.edit', 'title' => 'Edit', 'guard_name' => 'web'],
            ['heading' => 'Ra Document', 'name' => 'ra-document.delete', 'title' => 'Delete', 'guard_name' => 'web'],
            ['heading' => 'Ra Document', 'name' => 'ra-document.status', 'title' => 'Status', 'guard_name' => 'web'],

            ['heading' => 'Change Password', 'name' => 'change-password.edit', 'title' => 'Edit', 'guard_name' => 'web'],

            ['heading' => 'User', 'name' => 'user.view', 'title' => 'View', 'guard_name' => 'web'],
            ['heading' => 'User', 'name' => 'user.create', 'title' => 'Create', 'guard_name' => 'web'],
            ['heading' => 'User', 'name' => 'user.edit', 'title' => 'Edit', 'guard_name' => 'web'],
            ['heading' => 'User', 'name' => 'user.delete', 'title' => 'Delete', 'guard_name' => 'web'],
            ['heading' => 'User', 'name' => 'user.status', 'title' => 'Status', 'guard_name' => 'web'],
            ['heading' => 'User', 'name' => 'user.show-profile', 'title' => 'View Profile', 'guard_name' => 'web'],
            ['heading' => 'User', 'name' => 'user.edit-profile', 'title' => 'Edit Profile', 'guard_name' => 'web'],

            ['heading' => 'Fe Document', 'name' => 'fe-document.view', 'title' => 'View', 'guard_name' => 'web'],
            ['heading' => 'Fe Document', 'name' => 'fe-document.create', 'title' => 'Create', 'guard_name' => 'web'],
            ['heading' => 'Fe Document', 'name' => 'fe-document.edit', 'title' => 'Edit', 'guard_name' => 'web'],
            ['heading' => 'Fe Document', 'name' => 'fe-document.delete', 'title' => 'Delete', 'guard_name' => 'web'],
            ['heading' => 'Fe Document', 'name' => 'fe-document.status', 'title' => 'Status', 'guard_name' => 'web'],

            ['heading' => 'Offline & Online', 'name' => 'candidate-form.create', 'title' => 'Create', 'guard_name' => 'web'],
            ['heading' => 'Offline & Online', 'name' => 'candidate-form.view', 'title' => 'View', 'guard_name' => 'web'],
            ['heading' => 'Offline & Online', 'name' => 'candidate-form.edit', 'title' => 'Edit', 'guard_name' => 'web'],
            ['heading' => 'Offline & Online', 'name' => 'candidate-form.status', 'title' => 'Status', 'guard_name' => 'web'],
            ['heading' => 'Offline & Online', 'name' => 'candidate-form.delete', 'title' => 'Delete', 'guard_name' => 'web'],
            ['heading' => 'Offline & Online', 'name' => 'candidate-form.download', 'title' => 'Download', 'guard_name' => 'web'],
            ['heading' => 'Offline & Online', 'name' => 'emigrate-fe-id.edit', 'title' => 'Emigrate Fe Id', 'guard_name' => 'web'],

            ['heading' => 'Document Upload', 'name' => 'user-passport.view', 'title' => 'View', 'guard_name' => 'web'],
            ['heading' => 'Document Upload', 'name' => 'user-passport.create', 'title' => 'Create', 'guard_name' => 'web'],
            ['heading' => 'Document Upload', 'name' => 'user-passport.edit', 'title' => 'Edit', 'guard_name' => 'web'],
            ['heading' => 'Document Upload', 'name' => 'user-passport.delete', 'title' => 'Delete', 'guard_name' => 'web'],
            ['heading' => 'Document Upload', 'name' => 'user-passport.status', 'title' => 'Status', 'guard_name' => 'web'],

        ];
 
         foreach ($permissions as $permission) {
             Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
          }
    }
}
