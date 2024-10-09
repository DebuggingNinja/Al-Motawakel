<?php

namespace App\Models;

use App\Traits\AccountMethods;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
  use HasFactory, AccountMethods;
  protected $fillable = [
    'name',
    'code',
    'mark',
    'phone',
    'phone2',
    'email',
    'address',
    'balance',
    'active_account_id'
  ];

  public function sales(): HasMany
  {
    return $this->hasMany(Sale::class);
  }

  public function ledgers()
  {
    return $this->hasMany(Ledger::class);
  }
  public function orders()
  {
    return $this->hasMany(Order::class);
  }
  public function containers()
  {
    return $this->hasMany(Container::class);
  }

  public function reminder()
  {
    return $this->hasMany(ClientClaimReminder::class);
  }

}
