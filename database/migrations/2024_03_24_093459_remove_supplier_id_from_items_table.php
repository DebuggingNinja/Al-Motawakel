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
      $table->dropForeign(['supplier_id']);
      $table->dropColumn('supplier_id');
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
      $table->foreignId('supplier_id')->nullable()->constrained('suppliers', 'id')
        ->cascadeOnUpdate()->cascadeOnDelete();
    });
  }
};
