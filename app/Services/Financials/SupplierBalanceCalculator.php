<?php

namespace App\Services\Financials;

use App\Models\Account;
use App\Models\Supplier;
use App\Traits\WithBalanceSelfInitial;

class SupplierBalanceCalculator extends BalanceCalculator
{

  use WithBalanceSelfInitial;

  public function reCalculate(): BalanceCalculator
  {
    $this->model = Supplier::find($this->account->model_id);
    return $this->calculateItems()->calculateLedgers();
  }

  public function calculateItems(): BalanceCalculator
  {
    $this->balance_rmb += $this->account->supplierOrders->pluck('items')->flatten()->sum('total');
    return $this;
  }

  public function calculateLedgers(): BalanceCalculator
  {
    $dollar = $this->account->supplierLedgers->where('currency', 'usd');
    $rmb = $this->account->supplierLedgers->where('currency', 'rmb');
    $this->balance_rmb    += $rmb->sum('credit') - $rmb->sum('debit');
    $this->balance_dollar += $dollar->sum('credit') - $dollar->sum('debit');
    return $this;
  }

  public function loadAccount(): void
  {
    $this->account = Account::with([
      'supplierOrders' => function ($query) {
        $query->whereDoesntHave('items', function($query) {
          $query->whereNotIn('status', ['shipped', 'received', 'cancelled']);
        });
      },
      'supplierOrders.items' => function ($query) {
        $query->whereIn('status', ['shipped', 'received']);
      },
      'supplierLedgers',
    ])->find($this->account_id);
  }

}
