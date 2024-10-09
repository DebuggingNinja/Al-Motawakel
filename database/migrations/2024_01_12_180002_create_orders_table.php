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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('repository_id')->nullable()->constrained('repositories')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete()->cascadeOnUpdate();
      $table->unsignedInteger('file')->nullable();
      $table->unsignedInteger('registery')->nullable();
      $table->unsignedInteger('paper')->nullable();
      $table->enum('type', ['ship', 'buy_ship']);
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
    Schema::dropIfExists('orders');
  }
};
