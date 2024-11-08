<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Reset cached roles and permissions
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Permissions untuk entitas mobil
    Permission::create(['name' => 'create cars']);
    Permission::create(['name' => 'view cars']);
    Permission::create(['name' => 'edit cars']);
    Permission::create(['name' => 'delete cars']);

    // Permissions untuk entitas rental
    Permission::create(['name' => 'create rentals']);
    Permission::create(['name' => 'view rentals']);
    Permission::create(['name' => 'edit rentals']);
    Permission::create(['name' => 'delete rentals']);

    // Permissions untuk entitas pengembalian
    Permission::create(['name' => 'create returns']);
    Permission::create(['name' => 'view returns']);
    Permission::create(['name' => 'edit returns']);
    Permission::create(['name' => 'delete returns']);

    // Membuat roles dan mengassign permissions
    $admin = Role::create(['name' => 'admin']);
    $admin->givePermissionTo([
      'create cars',
      'view cars',
      'edit cars',
      'delete cars',
      'create rentals',
      'view rentals',
      'edit rentals',
      'delete rentals',
      'create returns',
      'view returns',
      'edit returns',
      'delete returns',
    ]);

    $customer = Role::create(['name' => 'customer']);
    $customer->givePermissionTo([
      'view cars',
      'create rentals',
      'view rentals',
      'create returns',
      'view returns'
    ]);
  }
}
