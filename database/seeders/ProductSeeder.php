<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Product::create([
        'name' => 'Arabica Akar Kelana',
        'description' => 'Biji kopi pilihan dengan aroma kacang dan cokelat.',
        'price' => 85000,
        'stock' => 20,
    ]);

    \App\Models\Product::create([
        'name' => 'Robusta Tanjung',
        'description' => 'Kopi robusta kuat dengan body yang tebal.',
        'price' => 60000,
        'stock' => 50,
    ]);
}
}