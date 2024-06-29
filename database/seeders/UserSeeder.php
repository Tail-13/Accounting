<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("users")->insert([
            "username" => "admin",
            "email" => "admin@admin.admin",
            "password" => Hash::make("@Admin1234"),
            "created_at" => now(),
        ]);
    }
}
