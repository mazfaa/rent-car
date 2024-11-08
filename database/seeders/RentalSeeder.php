<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Rental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // $users = User::whereHas('roles', function ($query) {
    //   $query->where('name', 'customer');
    // })->take(5)->get();

    // $cars = Car::all();

    // foreach ($users as $index => $user) {
    //   $start_date = Carbon::now()->addDays($index);
    //   $end_date = $start_date->copy()->addDays(3);

    //   Rental::create([
    //     'user_id' => $user->id,
    //     'car_id' => $cars->random()->id,
    //     'start_date' => $start_date,
    //     'end_date' => $end_date,
    //     'status' => $index % 2 == 0 ? 'Completed' : 'Rented',
    //     'total_price' => $cars->random()->daily_rate * (strtotime($end_date) - strtotime($start_date)) / 86400
    //   ]);
    // }
  }
}
