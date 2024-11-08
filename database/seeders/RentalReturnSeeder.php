<?php

namespace Database\Seeders;

use App\Models\Rental;
use App\Models\RentalReturn;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalReturnSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // $rentals = Rental::take(5)->get();

    // foreach ($rentals as $rental) {
    //   $return_date = Carbon::parse($rental->end_date)->addDay();
    //   $daily_rate = $rental->car->daily_rate;
    //   $days_rented = max($return_date->diffInDays($rental->start_date), 1); // Minimal 1 hari

    //   RentalReturn::create([
    //     'rental_id' => $rental->id,
    //     'return_date' => $return_date,
    //     'total_cost' => $days_rented * $daily_rate,
    //   ]);
    // }
  }
}
