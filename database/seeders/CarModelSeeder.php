<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $brands = \App\Models\Brand::all();

    $carModels = [
      ['name' => 'Avanza', 'brand_id' => $brands[0]->id],
      ['name' => 'Civic', 'brand_id' => $brands[1]->id],
      ['name' => 'Swift', 'brand_id' => $brands[2]->id],
      ['name' => 'Juke', 'brand_id' => $brands[3]->id],
      ['name' => 'Ranger', 'brand_id' => $brands[4]->id],
    ];

    \App\Models\CarModel::insert($carModels);
  }
}
