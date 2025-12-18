<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CitySeeder::class,
            OfficeSeeder::class,
            AdminUserSeeder::class,
            ServerTypeSeeder::class,
            SupportTypeSeeder::class,
            AssetCategorySeeder::class,
            DepartmentSeeder::class, // Registering the Department seeder
        ]);
    }
}
