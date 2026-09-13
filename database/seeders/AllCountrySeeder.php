<?php

namespace Database\Seeders;

use App\Models\AllCountry;
use Illuminate\Database\Seeder;

class AllCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        AllCountry::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = [
                ['name' => 'BAHRAIN', 'capital' => 'MANAMA', 'phonecode' => '+973'],
                ['name' => 'KUWAIT', 'capital' => 'KUWAIT CITY', 'phonecode' => '+965'],
                ['name' => 'OMAN', 'capital' => 'MUSCAT', 'phonecode' => '+968'],
                ['name' => 'QATAR', 'capital' => 'DOHA', 'phonecode' => '+974'],
                ['name' => 'SAUDI ARABIA', 'capital' => 'RIYADH', 'phonecode' => '+966'],
                ['name' => 'UNITED ARAB EMIRATES', 'capital' => 'ABU DHABI', 'phonecode' => '+971'],
        ];
        AllCountry::insert($data);
    }
}
