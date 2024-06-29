<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("roles")->insert([
            "name" => "admin",
            "description" => "admin",
            "code" => 0,
            "created_by" => 1,
            "created_at" => now()
        ]);

        DB::table("user_roles")->insert([
            "role_code" => 0,
            "user_id" => 1,
            "created_by" => 1,
            "created_at" => now()
        ]);
    }
}
