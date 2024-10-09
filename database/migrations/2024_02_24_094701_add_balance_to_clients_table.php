<?php

use App\Models\Client;
use App\Models\Ledger;
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
    Schema::table('clients', function (Blueprint $table) {
      $table->decimal('balance', 10, 2)->default(0.00)->after('address');
    });
    $this->balanceCalculator();
  }
  public function balanceCalculator()
  {
    // جمع debit و credit لكل client_id
    $balances = Ledger::select('client_id')
      ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
      ->groupBy('client_id')
      ->get();

    // تحديث جدول الـ clients بالرصيد الجديد
    foreach ($balances as $balance) {
      $client = Client::find($balance->client_id);
      // حساب الرصيد
      $newBalance = $balance->total_debit - $balance->total_credit;
      // تحديث الرصيد في جدول الـ clients
      $client->update(['balance' => $newBalance]);
    }
  }
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('clients', function (Blueprint $table) {
      $table->dropColumn('balance');
    });
  }
};
