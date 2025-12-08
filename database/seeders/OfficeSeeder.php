<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Office;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Office::create(['name' => 'Head Office - Lahore', 'city_id' => 1, 'address' => '123 Main St, Lahore']);
        Office::create(['name' => 'Regional Office - Karachi', 'city_id' => 2, 'address' => '456 Ocean Ave, Karachi']);
        Office::create(['name' => 'Sales Office - Islamabad', 'city_id' => 3, 'address' => '789 Capital Rd, Islamabad']);
        Office::create(['name' => 'Marketing Office - Faisalabad', 'city_id' => 4, 'address' => '101 Textile Blvd, Faisalabad']);
        Office::create(['name' => 'Support Office - Multan', 'city_id' => 5, 'address' => '212 Saint Cir, Multan']);
    }
}
