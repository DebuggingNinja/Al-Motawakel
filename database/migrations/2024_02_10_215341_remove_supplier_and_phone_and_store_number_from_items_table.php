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
    Schema::table('items', function (Blueprint $table) {
      $table->dropColumn('supplier');
      $table->dropColumn('phone');
      $table->dropColumn('store_number');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('items', function (Blueprint $table) {
      $table->string('supplier')->nullable();
      $table->string('phone')->nullable();
      $table->string('store_number')->nullable();
    });
  }
};
