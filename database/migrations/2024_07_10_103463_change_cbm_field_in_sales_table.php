<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::table('sales')->truncate();
    Schema::table('sales', function (Blueprint $table) {
      $table->dropColumn('cbm');
    });
    Schema::table('sales', function (Blueprint $table) {
      $table->double('cbm')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('sales', function (Blueprint $table) {
      $table->dropColumn('cbm');
      $table->bigInteger('cbm')->nullable()->change();
    });
  }
};
