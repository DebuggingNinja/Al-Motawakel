<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SupplierPermissionsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Permission::create(['name' => 'view suppliers']);
    Permission::create(['name' => 'add suppliers']);
    Permission::create(['name' => 'update suppliers']);
    Permission::create(['name' => 'delete suppliers']);
  }
}
