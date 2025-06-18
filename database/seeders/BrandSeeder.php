<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        DB::table('brands')->insert([
            'user' => 'Brand A',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('brands')->insert([
            'name' => 'Brand B',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
