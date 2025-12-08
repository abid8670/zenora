<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'abid',
            'email' => 'abid8670@gmail.com',
            'password' => Hash::make('polka@1122'),
            'office_id' => 1, // Assigning to Head Office - Lahore
            'status' => 'active',
        ]);
    }
}
