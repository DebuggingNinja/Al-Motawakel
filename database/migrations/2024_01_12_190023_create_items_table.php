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
    Schema::create('items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
      $table->string('carton_code')->nullable();
      $table->string('item')->nullable();
      $table->unsignedInteger('carton_quantity');
      $table->unsignedInteger('pieces_number')->nullable();
      $table->unsignedDouble('single_price')->nullable();
      $table->unsignedDouble('total')->nullable();
      $table->date('check_date')->nullable();
      $table->unsignedDouble('cbm');
      $table->unsignedDouble('weight');
      $table->enum('status', ['requested', 'checked', 'waiting', 'received', 'shipped', 'cancelled']);
      $table->string('supplier')->nullable();
      $table->string('phone')->nullable();
      $table->string('store_number')->nullable();
      $table->string('check_image')->nullable();
      $table->string('container_number')->nullable();
      $table->string('check_notes')->nullable();
      $table->string('receive_notes')->nullable();
      $table->string('cancelled_notes')->nullable();
      $table->string('notes')->nullable();
      $table->date('receive_date')->nullable();
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
    Schema::dropIfExists('items');
  }
};
