<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\ActivityType;

class ActivityTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ActivityType::create([
            'id' => 1, 
            'name' => 'Program Merdeka Belajar-Kampus Merdeka (MBKM) dan Hibah Lainnya', 
        ]);

        ActivityType::create([
            'id' => 2, 
            'name' => 'Program Puspresnas/Program Diktiristek', 
        ]);

        ActivityType::create([
            'id' => 2, 
            'name' => 'Program Mandiri', 
        ]);
    }
}
