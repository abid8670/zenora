<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::create(['name' => 'Lahore']);
        City::create(['name' => 'Karachi']);
        City::create(['name' => 'Islamabad']);
        City::create(['name' => 'Faisalabad']);
        City::create(['name' => 'Multan']);
        City::create(['name' => 'Rawalpindi']);
        City::create(['name' => 'Peshawar']);
        City::create(['name' => 'Quetta']);
    }
}
