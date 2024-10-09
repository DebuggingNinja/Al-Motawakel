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
    Schema::create('client_claim_reminders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('client_id')->constrained('clients');
      $table->text('description')->nullable();
      $table->date('due_date')->nullable();
      $table->foreignId('extended_client_claim_reminder_id')->nullable()->constrained('client_claim_reminders')->onDelete('cascade');
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
    Schema::dropIfExists('client_claim_reminders');
  }
};
