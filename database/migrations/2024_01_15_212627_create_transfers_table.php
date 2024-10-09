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
    Schema::create('transfers', function (Blueprint $table) {
      $table->id();
      $table->date('date');
      $table->string('from');
      $table->double('amount_usd', 10, 2);
      $table->double('amount_rmb', 10, 2);
      $table->double('rate', 10, 2);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('transfers');
  }
};
