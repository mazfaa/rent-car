<?php

namespace Database\Seeders;

use App\Model\Car;
use App\Model\User;
use App\Models\Car as ModelsCar;
use App\Models\User as ModelsUser;
use Illuminate\Database\Console\Seeds\WithoutCar_Model_idEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $users = ModelsUser::whereHas('roles', function ($query) {
      $query->whereName('admin');
    })->get();

    // Data mobil dengan user_id yang ditentukan
    $cars = [
      ['car_model_id' => '1', 'plate_number' => 'D 1234 ABC', 'daily_rate' => 300000, 'user_id' => $users->random()->id],
      ['car_model_id' => '2', 'plate_number' => 'D 5678 DEF', 'daily_rate' => 400000, 'user_id' => $users->random()->id],
      ['car_model_id' => '3', 'plate_number' => 'D 9012 GHI', 'daily_rate' => 350000, 'user_id' => $users->random()->id],
      ['car_model_id' => '4', 'plate_number' => 'D 3456 JKL', 'daily_rate' => 370000, 'user_id' => $users->random()->id],
      ['car_model_id' => '5', 'plate_number' => 'D 7890 MNO', 'daily_rate' => 450000, 'user_id' => $users->random()->id],
    ];

    foreach ($cars as $car) {
      ModelsCar::create($car);
    }
  }
}
