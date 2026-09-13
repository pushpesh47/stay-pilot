<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $admin = User::create([
            'name' => 'Globe Travels', 
            'email' => 'emigrate.globetravelservices@gmail.com',
            'mobile' => '(+91) 6392871088',
            'address' => 'Vikas Nagar , Sector 7, New Vikas Colony, UP 226022',
            'password' => Hash::make('12345678'),
            'status' => 'active',
            'role_id' => 1,
        ]);
        $admin->assignRole('Admin');

    }
}