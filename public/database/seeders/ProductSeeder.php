<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        $now = now();
        DB::table('products')->insert([
            'name' => 'Produk A',
            'price' => 150,
            'quantity' => 40,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('products')->insert([
            'name' => 'Produk B',
            'price' => 200,
            'quantity' => 68,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
