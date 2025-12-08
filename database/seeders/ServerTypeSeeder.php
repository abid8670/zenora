<?php

namespace Database\Seeders;

use App\Models\ServerType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Web Server'],
            ['name' => 'Database Server'],
            ['name' => 'Mail Server'],
            ['name' => 'File Server'],
            ['name' => 'VPN Server'],
            ['name' => 'Proxy Server'],
            ['name' => 'Other'],
        ];

        foreach ($types as $type) {
            ServerType::create($type);
        }
    }
}
