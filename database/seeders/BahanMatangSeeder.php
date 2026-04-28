<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BahanMatangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\BahanBakuMatang::create(['nama_biji' => 'Arabica Roasted Bulk']);
        \App\Models\BahanBakuMatang::create(['nama_biji' => 'Robusta Roasted Bulk']);
        \App\Models\BahanBakuMatang::create(['nama_biji' => 'House Blend Roasted']);
    }
}
