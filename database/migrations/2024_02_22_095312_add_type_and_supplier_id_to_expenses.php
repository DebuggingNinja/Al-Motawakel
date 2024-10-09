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
    Schema::table('expenses', function (Blueprint $table) {
      $table->string('type'); // Add 'type' field as string
      $table->unsignedBigInteger('supplier_id')->nullable(); // Add 'supplier_id' as foreign key

      // Define foreign key constraint
      $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('expenses', function (Blueprint $table) {
      $table->dropColumn('type');
      $table->dropForeign(['supplier_id']);
      $table->dropColumn('supplier_id');
    });
  }
};
