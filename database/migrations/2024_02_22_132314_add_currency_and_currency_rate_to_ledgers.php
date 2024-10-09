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
    Schema::table('ledgers', function (Blueprint $table) {
      $table->enum('currency', ['rmb', 'usd'])->default('rmb')->after('balance')->nullable();
      $table->double('currency_rate')->after('currency')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('ledgers', function (Blueprint $table) {
      $table->dropColumn('currency');
      $table->dropColumn('currency_rate');
    });
  }
};
