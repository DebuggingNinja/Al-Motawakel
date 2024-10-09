<?php

use App\Models\Supplier;
use App\Models\SupplierLedger;
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
      $table->string('action')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('supplier_ledgers', function (Blueprint $table) {
      $table->dropColumn('action');
    });
  }
};
