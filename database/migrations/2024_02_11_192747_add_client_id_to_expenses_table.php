<?php

use App\Models\Expense;
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
      $table->foreignId('client_id')->nullable()->constrained('clients', 'id')->cascadeOnUpdate()->cascadeOnDelete();
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
      $table->dropForeignIdFor(Expense::class, 'client_id');
    });
  }
};
