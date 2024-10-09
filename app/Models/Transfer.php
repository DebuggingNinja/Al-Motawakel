<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transfer extends Model
{
  use HasFactory;
  protected $fillable = [
    'date',
    'from',
    'number',
    'amount_usd',
    'amount_rmb',
    'rate',
  ];
  public function ledgers(): HasMany
  {
    return $this->hasMany(Ledger::class);
  }
}
