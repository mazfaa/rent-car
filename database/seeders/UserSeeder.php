<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Tambah role jika belum ada
    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $customerRole = Role::firstOrCreate(['name' => 'customer']);

    // Seed user admin
    User::create([
      'name' => 'Admin User',
      'email' => 'admin@example.com',
      'password' => bcrypt('password'),
      'address' => 'Jalan Kebon Jeruk No. 1, Jakarta',
      'phone_number' => '081234567890',
      'driving_license_number' => 'B123456789',
    ])->assignRole($adminRole);

    // Seed user pelanggan
    for ($i = 1; $i <= 5; $i++) {
      User::create([
        'name' => 'Customer ' . $i,
        'email' => 'customer' . $i . '@example.com',
        'password' => bcrypt('password'),
        'address' => 'Jalan Kebon Jeruk No. ' . $i . ', Jakarta',
        'phone_number' => '08123456789' . $i,
        'driving_license_number' => 'CUST' . $i . 'DL12345',
      ])->assignRole($customerRole);
    }
  }
}
