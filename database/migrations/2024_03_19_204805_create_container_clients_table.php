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
    Schema::create('container_clients', function (Blueprint $table) {
      $table->id();
      $table->foreignId('container_id')->nullable()->constrained('containers')->cascadeOnUpdate()->nullOnDelete();
      $table->unsignedBigInteger('quantity')->default(0);
      $table->unsignedDouble('meter_price')->default(0);
      $table->unsignedDouble('total')->default(0);
      $table->string('notes')->nullable();
      $table->foreignId('client_id')->constrained('clients')->cascadeOnUpdate()->cascadeOnDelete();
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
    Schema::dropIfExists('container_clients');
  }
};
