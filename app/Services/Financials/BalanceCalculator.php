<?php

namespace App\Services\Financials;

use App\Models\Account;
use App\Models\Client;
use App\Models\ShippingCompany;
use App\Models\Supplier;

abstract class BalanceCalculator
{

  protected int $account_id;
  protected ?Account $account;
  protected float $balance_dollar, $balance_rmb;

  protected Client|Supplier|ShippingCompany|null $model;

  public function __construct(int|Account $account)
  {
      if(is_numeric($account)) {
        $this->account_id = $account;
        $this->loadAccount();
      }else{
        $this->account_id = $account->id;
        $this->account = $account;
      }
  }

  public function calculate(): BalanceCalculator
  {
    if(!$this->account) return $this;
    return $this->calculateInitialBalance()->reCalculate();
  }

  public function calculateInitialBalance(): BalanceCalculator
  {
    return $this->onInitial();
  }

  public function onInitial(): BalanceCalculator
  {
    $this->balance_dollar = $this->account->starting_balance_dollar;
    $this->balance_rmb = $this->account->starting_balance_rmb;
    return $this;
  }

  public function save(): bool
  {
      return $this->account && $this->account->update([
        'total_dollar' => $this->balance_dollar,
        'total_rmb' => $this->balance_rmb
      ]);
  }

  public function restartNewAccount(): bool
  {
    if(!$this->account) return false;
    $new = $this->replicate();

    if(!$this->account->update([
      'end_date' => date('Y-m-d')
    ])) return false;

    $this->account = $new;
    return true;
  }

  private function replicate(): Account
  {
    $new = $this->account->replicate();
    $new->created_at = $new->updated_at = now();
    $new->start_date = date('Y-m-d');
    $new->starting_balance_dollar = $this->account->total_dollar;
    $new->starting_balance_rmb = $this->account->total_rmb;
    $new->total_rmb = $new->total_dollar = 0;
    $new->save();
    return $new;
  }

  public abstract function loadAccount(): void;

  public abstract function reCalculate(): BalanceCalculator;

  public function getAccount(): ?Account
  {
    return $this->account;
  }

  public function getDollarBalance(): float
  {
    return $this->balance_dollar;
  }

  public function getRMBBalance(): float
  {
    return $this->balance_rmb;
  }

}
