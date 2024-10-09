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
    Schema::table('container_items', function (Blueprint $table) {
      $table->boolean('is_sold')->default(false)->after('notes');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('container_items', function (Blueprint $table) {
      $table->dropColumn('is_sold');
    });
  }
};
