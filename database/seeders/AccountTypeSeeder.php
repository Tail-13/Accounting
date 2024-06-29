<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ["income", "expense", "asset", 'liability', 'equity'];
        foreach ($types as $type) {
            DB::table("account_types")->insert([
                "name"=> $type,
                "description" => "-",
                "required" => true,
                "created_by" => 1,
                "created_at" => now(),
            ]);
        }
    }
}
