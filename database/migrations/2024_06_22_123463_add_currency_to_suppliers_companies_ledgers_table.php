<?php

use App\Models\Client;
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
    Schema::table('supplier_ledgers', function (Blueprint $table) {
      $table->enum('currency', ['rmb', 'usd'])->default('rmb')->after('balance')->nullable();
    });
    Schema::table('shipper_ledgers', function (Blueprint $table) {
      $table->enum('currency', ['rmb', 'usd'])->default('rmb')->after('balance')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
  }
};
