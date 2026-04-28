<?php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'info@urlwebwala.com'],
            [
                'name' => 'Admin',
                'user_type' => UserTypeEnum::ADMIN,
                'phone' => '1234567890',
                'password' => Hash::make('admin'),
                'created_by' => 1,
                'created_at' => now(),
                'ip_address' => Request::ip(),
            ]
        );
    }
}
