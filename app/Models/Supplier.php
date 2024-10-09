<?php

namespace App\Models;

use App\Services\Financials\SupplierBalanceCalculator;
use App\Traits\AccountMethods;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
  use HasFactory, AccountMethods;
  protected $fillable = [
    'name',
    'code',
    'phone',
    'store_number',
    'balance',
    'active_account_id'
  ];


  public function orders(): HasMany
  {
    return $this->hasMany(Order::class);
  }
  public function items(): HasMany
  {
    return $this->hasMany(Item::class);
  }
  public function ledgers()
  {
    return $this->hasMany(SupplierLedger::class);
  }

  public function reminder()
  {
    return $this->hasMany(SupplierPaymentReminder::class);
  }

//  protected static function boot()
//  {
//    parent::boot();
//    static::created(function ($supplier) {
//      DB::transaction(function () use ($supplier) {
//        SupplierLedger::create([
//          'date' => now(),
//          'description' => "Opening Account",
//          'credit' => 0,
//          'debit' => 0,
//          'action' => 'opining',
//          'balance' => 0,
//          'supplier_id' => $supplier->id,
//          'account_id' => $supplier->currentAccountID()
//        ]);
//
//        if($supplier->currentAccountID()) SupplierBalanceCalculator::init($supplier->currentAccountID())->calculate()->save();
//      });
//    });
//  }
}
