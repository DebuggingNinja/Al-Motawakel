<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierLedger extends Model
{
  use HasFactory;
  protected $fillable = ['date', 'description', 'debit', 'credit', 'balance', 'supplier_id', 'action', 'account_id'];

  public function account(): BelongsTo
  {
    return $this->belongsTo(Account::class, 'account_id');
  }

  public function supplier(): BelongsTo
  {
    return $this->belongsTo(Supplier::class);
  }
}
