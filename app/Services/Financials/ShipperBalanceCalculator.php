<?php

namespace App\Services\Financials;

use App\Models\Account;
use App\Models\ShippingCompany;
use App\Traits\WithBalanceSelfInitial;

class ShipperBalanceCalculator extends BalanceCalculator
{

  use WithBalanceSelfInitial;

  public function reCalculate(): BalanceCalculator
  {
    $this->model = ShippingCompany::find($this->account->model_id);
    return $this->calculateContainers()->calculateLedgers();
  }

  public function calculateContainers(): BalanceCalculator
  {
    $this->balance_rmb    += $this->account->companyContainers->sum('cost_rmb');
    $this->balance_rmb    -= $this->account->companyContainers->sum('back_rmb');
    $this->balance_dollar += $this->account->companyContainers->sum('cost_dollar');
    return $this;
  }

  public function calculateLedgers(): BalanceCalculator
  {
    $dollar = $this->account->shipperLedgers->where('currency', 'usd');
    $rmb = $this->account->shipperLedgers->where('currency', 'rmb');
    $this->balance_rmb    += $rmb->sum('credit') - $rmb->sum('debit');
    $this->balance_dollar += $dollar->sum('credit') - $dollar->sum('debit');
    return $this;
  }

  public function loadAccount(): void
  {
    $this->account = Account::with([
      'companyContainers',
      'shipperLedgers',
    ])->find($this->account_id);
  }

}
