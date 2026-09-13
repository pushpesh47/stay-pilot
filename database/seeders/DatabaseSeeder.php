<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            DefaultUserSeeder::class,
            AllCountrySeeder::class,
            AllStateSeeder::class,
            AllCitySeeder::class,
            SettingSeeder::class,
        ]);
    }
}
