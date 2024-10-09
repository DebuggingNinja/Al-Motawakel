<?php

namespace App\Services\Financials;

use App\Models\Account;
use App\Models\Client;
use App\Traits\WithBalanceSelfInitial;

class ClientBalanceCalculator extends BalanceCalculator
{

  use WithBalanceSelfInitial;

  public function onInitial(): BalanceCalculator
  {
    $this->balance_dollar = $this->account->starting_balance_dollar;
    if($this->account->starting_balance_rmb)
      $this->balance_dollar += ($this->account->starting_balance_rmb * ($this->account->dollar_rate ?? 1));
    $this->balance_rmb = 0;
    return $this;
  }

  public function reCalculate(): BalanceCalculator
  {
    $this->model = Client::find($this->account->model_id);
    return $this->calculateItems()->calculateLedgers();
  }

  public function calculateItems(): BalanceCalculator
  {
    $this->balance_dollar -= $this->account->clientContainers->map(function ($c){
        return $c->cost_rmb / $c->dollar_price;
    })->sum();
    $this->balance_dollar -= $this->account->clientContainers->sum('cost_dollar') +
      $this->account->clientContainers->pluck('items')->flatten()->sum('total');
    return $this;
  }

  public function calculateLedgers(): BalanceCalculator
  {
    $dollar = $this->account->ledgers->where('currency', 'usd');
    $rmb = $this->account->ledgers->where('currency', 'rmb');
    $this->balance_rmb    += $rmb->sum('credit') - $rmb->sum('debit');
    $this->balance_dollar += $dollar->sum('credit') - $dollar->sum('debit');
    return $this;
  }

  public function loadAccount(): void
  {
    $this->account = Account::with([
      'clientContainers.items',
      'ledgers',
    ])->find($this->account_id);
  }

}
