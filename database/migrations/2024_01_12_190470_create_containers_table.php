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
    Schema::create('containers', function (Blueprint $table) {
      $table->id();
      $table->string('number')->unique();
      $table->string('lock_number');
      $table->date('shipping_date')->default(now());
      $table->date('est_arrive_date');
      $table->string('destination');
      $table->enum('shipping_type', ['complete', 'partial']);
      $table->foreignId('shipping_company_id')->nullable()->constrained('shipping_companies')->cascadeOnUpdate()->nullOnDelete();
      $table->foreignId('broker_id')->nullable()->constrained('brokers')->cascadeOnUpdate()->nullOnDelete();
      $table->foreignId('repository_id')->nullable()->constrained('repositories')->cascadeOnUpdate()->nullOnDelete();
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
    Schema::dropIfExists('containers');
  }
};
