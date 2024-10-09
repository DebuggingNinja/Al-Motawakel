<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'model_id',
        'total_rmb',
        'total_dollar',
        'dollar_rate',
        'start_date',
        'end_date'
    ];

  public function clientOrders(): HasMany
  {
    return $this->hasMany(Order::class, 'client_account_id');
  }

  public function supplierOrders(): HasMany
  {
    return $this->hasMany(Order::class, 'supplier_account_id');
  }

  public function clientContainers(): HasMany
  {
    return $this->hasMany(Container::class, 'client_account_id');
  }

  public function companyContainers(): HasMany
  {
    return $this->hasMany(Container::class, 'company_account_id');
  }

  public function ledgers(): HasMany
  {
    return $this->hasMany(Ledger::class, 'account_id');
  }

  public function shipperLedgers(): HasMany
  {
    return $this->hasMany(ShipperLedger::class, 'account_id');
  }

  public function supplierLedgers(): HasMany
  {
    return $this->hasMany(SupplierLedger::class, 'account_id');
  }

}
