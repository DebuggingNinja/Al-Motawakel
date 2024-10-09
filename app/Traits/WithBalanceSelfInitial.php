<?php

namespace App\Traits;

use App\Models\Account;
use App\Services\Financials\BalanceCalculator;
use Illuminate\Database\Eloquent\Collection;

trait WithBalanceSelfInitial
{

  public static function init(int|Account $account): BalanceCalculator
  {
    return new self($account);
  }

}
