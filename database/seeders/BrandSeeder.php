<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $brands = [
      ['name' => 'Toyota'],
      ['name' => 'Honda'],
      ['name' => 'Nissan'],
      ['name' => 'Suzuki'],
      ['name' => 'Ford'],
    ];

    foreach ($brands as $brand) {
      \App\Models\Brand::create($brand);
    }
  }
}
