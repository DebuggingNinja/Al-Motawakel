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
    Schema::table('shipping_companies', function (Blueprint $table) {
      $table->decimal('balance', 10, 2)->default(0.00);
    });
    $this->balanceCalculator();
  }
  public function balanceCalculator()
  {
    // جمع debit و credit لكل client_id
    $balances = ShipperLedger::select('shipping_company_id')
      ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
      ->groupBy('shipping_company_id')
      ->get();

    // تحديث جدول الـ clients بالرصيد الجديد
    foreach ($balances as $balance) {
      $supplier = ShippingCompany::find($balance->shipping_company_id);
      // حساب الرصيد
      $newBalance = $balance->total_debit - $balance->total_credit;
      // تحديث الرصيد في جدول الـ clients
      $supplier->update(['balance' => $newBalance]);
    }
  }
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shipping_companies', function (Blueprint $table) {
      $table->dropColumn('balance');
    });
  }
};
