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
      $table->dropColumn('total');
      $table->dropColumn('notes');
    });
    Schema::table('container_items', function (Blueprint $table) {
      $table->double('total')->default(0)->after('container_id');
      $table->string('notes')->nullable()->after('total');
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
    });
  }
};
