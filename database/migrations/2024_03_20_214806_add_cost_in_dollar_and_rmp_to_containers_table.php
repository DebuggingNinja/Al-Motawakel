<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
    Schema::table('containers', function (Blueprint $table) {
      $table->double('cost_rmb')->nullable();
      $table->double('cost_dollar')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('containers', function (Blueprint $table) {
      $table->dropColumn('cost_rmb');
      $table->dropColumn('cost_dollar');
    });
  }
};
