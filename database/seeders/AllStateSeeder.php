<?php

namespace Database\Seeders;

use App\Models\AllState;
use Illuminate\Database\Seeder;

class AllStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        AllState::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = [
            ['name' => '\'Isa', 'country_id' => 1],
            ['name' => 'Badiyah', 'country_id' => 1],
            ['name' => 'Hidd', 'country_id' => 1],
            ['name' => 'Jidd Hafs', 'country_id' => 1],
            ['name' => 'Mahama', 'country_id' => 1],
            ['name' => 'Manama', 'country_id' => 1],
            ['name' => 'Sitrah', 'country_id' => 1],
            ['name' => 'al-Manamah', 'country_id' => 1],
            ['name' => 'al-Muharraq', 'country_id' => 1],
            ['name' => 'ar-Rifa\'a', 'country_id' => 1],
            ['name' => 'Al Asimah', 'country_id' => 2],
            ['name' => 'Hawalli', 'country_id' => 2],
            ['name' => 'Mishref', 'country_id' => 2],
            ['name' => 'Qadesiya', 'country_id' => 2],
            ['name' => 'Safat', 'country_id' => 2],
            ['name' => 'Salmiya', 'country_id' => 2],
            ['name' => 'al-Ahmadi', 'country_id' => 2],
            ['name' => 'al-Farwaniyah', 'country_id' => 2],
            ['name' => 'al-Jahra', 'country_id' => 2],
            ['name' => 'al-Kuwayt', 'country_id' => 2],
            ['name' => 'Al Buraimi', 'country_id' => 3],
            ['name' => 'Dhufar', 'country_id' => 3],
            ['name' => 'Masqat', 'country_id' => 3],
            ['name' => 'Musandam', 'country_id' => 3],
            ['name' => 'Rusayl', 'country_id' => 3],
            ['name' => 'Wadi Kabir', 'country_id' => 3],
            ['name' => 'ad-Dakhiliyah', 'country_id' => 3],
            ['name' => 'adh-Dhahirah', 'country_id' => 3],
            ['name' => 'al-Batinah', 'country_id' => 3],
            ['name' => 'ash-Sharqiyah', 'country_id' => 3],
            ['name' => 'Doha', 'country_id' => 4],
            ['name' => 'Jarian-al-Batnah', 'country_id' => 4],
            ['name' => 'Umm Salal', 'country_id' => 4],
            ['name' => 'ad-Dawhah', 'country_id' => 4],
            ['name' => 'al-Ghuwayriyah', 'country_id' => 4],
            ['name' => 'al-Jumayliyah', 'country_id' => 4],
            ['name' => 'al-Khawr', 'country_id' => 4],
            ['name' => 'al-Wakrah', 'country_id' => 4],
            ['name' => 'ar-Rayyan', 'country_id' => 4],
            ['name' => 'ash-Shamal', 'country_id' => 4],
            ['name' => 'Al Khobar', 'country_id' => 5],
            ['name' => 'Aseer', 'country_id' => 5],
            ['name' => 'Ash Sharqiyah', 'country_id' => 5],
            ['name' => 'Asir', 'country_id' => 5],
            ['name' => 'Central Province', 'country_id' => 5],
            ['name' => 'Eastern Province', 'country_id' => 5],
            ['name' => 'Ha\'il', 'country_id' => 5],
            ['name' => 'Jawf', 'country_id' => 5],
            ['name' => 'Jizan', 'country_id' => 5],
            ['name' => 'Makkah', 'country_id' => 5],
            ['name' => 'Najran', 'country_id' => 5],
            ['name' => 'Qasim', 'country_id' => 5],
            ['name' => 'Tabuk', 'country_id' => 5],
            ['name' => 'Western Province', 'country_id' => 5],
            ['name' => 'al-Bahah', 'country_id' => 5],
            ['name' => 'al-Hudud-ash-Shamaliyah', 'country_id' => 5],
            ['name' => 'al-Madinah', 'country_id' => 5],
            ['name' => 'ar-Riyad', 'country_id' => 5],
            ['name' => 'Abu Dhabi', 'country_id' => 6],
            ['name' => 'Ajman', 'country_id' => 6],
            ['name' => 'Dubai', 'country_id' => 6],
            ['name' => 'Ras al-Khaymah', 'country_id' => 6],
            ['name' => 'Sharjah', 'country_id' => 6],
            ['name' => 'Umm Al Quwain', 'country_id' => 6],
            ['name' => 'al-Fujayrah', 'country_id' => 6],
        ];
        AllState::insert($data);
    }
}
