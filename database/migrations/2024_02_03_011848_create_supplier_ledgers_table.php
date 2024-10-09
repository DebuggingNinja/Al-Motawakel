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
    Schema::create('supplier_ledgers', function (Blueprint $table) {
      $table->id();
      $table->foreignId('supplier_id')->nullable()->constrained('suppliers', 'id')->cascadeOnUpdate()->cascadeOnDelete();
      $table->timestamp('date');
      $table->string('description');
      $table->double('debit', 10, 2)->default(0);
      $table->double('credit', 10, 2)->default(0);
      $table->double('balance', 10, 2)->default(0);
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
    Schema::dropIfExists('supplier_ledgers');
  }
};
