<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WebsiteSetting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
                ['web_mobile_number' => '(+91) 6392871088', 
                'web_email_id' => 'emigrate.globetravelservices@gmail.com', 
                'company_name' => 'Globe Travels', 
                'footer_description' => 'All rights reserved.', 
                'company_address' => 'Vikas Nagar Sector 7, New Vikas Colony, Lucknow, UP 226022', 
                'website_logo' => 'logo.png', 
                'copyright_text' => "Copyright ©"],
        ];
        WebsiteSetting::insert($data);
    }
}
