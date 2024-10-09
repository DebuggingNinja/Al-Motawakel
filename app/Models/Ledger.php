<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ledger extends Model
{
  use HasFactory;
  protected $fillable = [
    'date',
    'description',
    'action',
    'debit',
    'credit',
    'balance',
    'client_id',
    'transfer_id',
    'currency',
    'currency_rate',
    'account_id'
  ];

  public function account(): BelongsTo
  {
    return $this->belongsTo(Account::class, 'account_id');
  }

  public function client(): BelongsTo
  {
    return $this->belongsTo(Client::class);
  }
  public function transfer(): BelongsTo
  {
    return $this->belongsTo(Transfer::class);
  }
}
