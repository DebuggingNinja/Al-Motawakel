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
    Schema::create('container_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('container_id')->nullable()->constrained('containers')->cascadeOnUpdate()->nullOnDelete();
      $table->unsignedDouble('meter_price');
      $table->unsignedDouble('total');
      $table->string('notes');
      $table->foreignId('item_id')->constrained('items')->cascadeOnUpdate()->cascadeOnDelete();
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
    Schema::dropIfExists('container_items');
  }
};
