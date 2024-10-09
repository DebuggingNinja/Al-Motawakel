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
    Schema::create('sales', function (Blueprint $table) {
      $table->id();
      $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete()->cascadeOnUpdate();
      $table->foreignId('item_id')->constrained('items')->cascadeOnDelete()->cascadeOnUpdate();
      $table->text('quantity')->nullable();
      $table->text('dozen_quantity')->nullable();
      $table->text('price')->nullable();
      $table->text('dozen_price')->nullable();
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
    Schema::dropIfExists('sales');
  }
};
