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
    Schema::create('company_payment_reminders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('shipping_company_id')->constrained('shipping_companies');
      $table->text('description')->nullable();
      $table->date('payment_date')->nullable();
      $table->enum('status', ['pending', 'paid', 'extended', 'overdue'])->default('pending');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('company_payment_reminders');
  }
};
