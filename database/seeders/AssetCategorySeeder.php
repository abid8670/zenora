<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Laptops'],
            ['name' => 'Monitors'],
            ['name' => 'Keyboards'],
            ['name' => 'Mice'],
            ['name' => 'Printers'],
            ['name' => 'Headsets'],
            ['name' => 'Webcams'],
            ['name' => 'Docking Stations'],
            ['name' => 'Office Chairs'],
            ['name' => 'Desks'],
        ];

        foreach ($categories as $category) {
            AssetCategory::create($category);
        }
    }
}
