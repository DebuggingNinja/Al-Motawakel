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
   *
   * @return void
   */
  public function run()
  {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::create(['name' => 'view users']);
    Permission::create(['name' => 'add users']);
    Permission::create(['name' => 'update users']);
    Permission::create(['name' => 'delete users']);

    Permission::create(['name' => 'view clients']);
    Permission::create(['name' => 'add clients']);
    Permission::create(['name' => 'update clients']);
    Permission::create(['name' => 'delete clients']);

    Permission::create(['name' => 'view shipping companies']);
    Permission::create(['name' => 'add shipping companies']);
    Permission::create(['name' => 'update shipping companies']);
    Permission::create(['name' => 'delete shipping companies']);

    Permission::create(['name' => 'view repositories']);
    Permission::create(['name' => 'add repositories']);
    Permission::create(['name' => 'update repositories']);
    Permission::create(['name' => 'delete repositories']);

    Permission::create(['name' => 'view brokers']);
    Permission::create(['name' => 'add brokers']);
    Permission::create(['name' => 'update brokers']);
    Permission::create(['name' => 'delete brokers']);

    Permission::create(['name' => 'view buy ship orders']);
    Permission::create(['name' => 'add buy ship orders']);
    Permission::create(['name' => 'update buy ship orders']);
    Permission::create(['name' => 'delete buy ship orders']);

    Permission::create(['name' => 'view buy ship items']);
    Permission::create(['name' => 'add buy ship items']);
    Permission::create(['name' => 'update buy ship items']);
    Permission::create(['name' => 'delete buy ship items']);

    Permission::create(['name' => 'view ship orders']);
    Permission::create(['name' => 'add ship orders']);
    Permission::create(['name' => 'update ship orders']);
    Permission::create(['name' => 'delete ship orders']);

    Permission::create(['name' => 'view ship items']);
    Permission::create(['name' => 'add ship items']);
    Permission::create(['name' => 'update ship items']);
    Permission::create(['name' => 'delete ship items']);

    Permission::create(['name' => 'change items status']);

    Permission::create(['name' => 'view containers']);
    Permission::create(['name' => 'add containers']);
    Permission::create(['name' => 'update containers']);
    Permission::create(['name' => 'delete containers']);

    Permission::create(['name' => 'view container items']);
    Permission::create(['name' => 'add container items']);
    Permission::create(['name' => 'update container items']);
    Permission::create(['name' => 'delete container items']);

    Permission::create(['name' => 'view ledgers']);
    Permission::create(['name' => 'add ledgers']);
    Permission::create(['name' => 'delete ledgers']);

    $role = Role::create(['name' => 'super admin']);
    $role->givePermissionTo(Permission::all());
  }
}
