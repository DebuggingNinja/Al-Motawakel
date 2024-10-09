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
    Schema::create('accounts', function (Blueprint $table) {
        $table->id();
        $table->string('model');
        $table->unsignedBigInteger('model_id');
        $table->double('starting_balance_dollar')->default(0);
        $table->double('starting_balance_rmb')->default(0);
        $table->double('total_rmb')->default(0);
        $table->double('total_dollar')->default(0);
        $table->double('dollar_rate')->default(0);
        $table->date('start_date');
        $table->date('end_date')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('accounts');
  }
};
