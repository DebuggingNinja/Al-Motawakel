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
    Schema::table('containers', function (Blueprint $table) {
      $table->dropForeign(['supplier_account_id']);
      $table->dropColumn('supplier_account_id');
    });
    Schema::table('containers', function (Blueprint $table) {
      $table->foreignId('company_account_id')->nullable()->constrained('accounts')->nullOnDelete()->cascadeOnUpdate();
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
