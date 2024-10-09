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
    Schema::table('clients', function (Blueprint $table) {
      $table->foreignId('active_account_id')->nullable()->constrained('accounts')->nullOnDelete()->cascadeOnUpdate();
    });
    Schema::table('suppliers', function (Blueprint $table) {
      $table->foreignId('active_account_id')->nullable()->constrained('accounts')->nullOnDelete()->cascadeOnUpdate();
    });
    Schema::table('shipping_companies', function (Blueprint $table) {
      $table->foreignId('active_account_id')->nullable()->constrained('accounts')->nullOnDelete()->cascadeOnUpdate();
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
