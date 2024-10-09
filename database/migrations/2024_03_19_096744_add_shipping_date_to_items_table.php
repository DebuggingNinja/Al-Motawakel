<?php

use App\Models\ShipperLedger;
use App\Models\ShippingCompany;
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
    Schema::table('items', function (Blueprint $table) {
      $table->dateTime('shipping_date')->nullable();
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
      $table->dropColumn('shipping_date');
    });
  }
};
