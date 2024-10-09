<?php

namespace App\Traits;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait AccountMethods
{

  public function activeAccount(): BelongsTo
  {
    return $this->belongsTo(Account::class, 'active_account_id');
  }

  public function currentAccountID(): ?int
  {
    return $this->active_account_id ?? $this->currentAccount()?->id ?? null;
  }

  public function currentAccount(): ?Account
  {
    return Account::where('model', self::class)->where('model_id', $this->id)
      ->whereNull('end_date')->first();
  }

  public function allAccounts(): ?Collection
  {
    return Account::where('model', self::class)->where('model_id', $this->id)->latest()->get();
  }

  public function getAccount($id): ?Account
  {
    return Account::where('model', self::class)->where('model_id', $this->id)->findOrFail($id);
  }

}
