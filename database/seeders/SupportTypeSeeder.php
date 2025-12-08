<?php

namespace Database\Seeders;

use App\Models\SupportType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supportTypes = [
            ['name' => 'Hardware Issue'],
            ['name' => 'Software Installation'],
            ['name' => 'Network Problem'],
            ['name' => 'Password Reset'],
            ['name' => 'Email Account Issue'],
            ['name' => 'Printer Problem'],
            ['name' => 'VPN Access'],
            ['name' => 'New User Account'],
            ['name' => 'File Access Request'],
            ['name' => 'Application Error'],
            ['name' => 'Other'],
        ];

        foreach ($supportTypes as $type) {
            SupportType::create($type);
        }
    }
}
