<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipperLedger extends Model
{
  use HasFactory;
  protected $fillable = ['date', 'currency', 'description', 'debit', 'credit', 'balance', 'shipping_company_id', 'action', 'account_id'];

  public function account(): BelongsTo
  {
    return $this->belongsTo(Account::class, 'account_id');
  }

  public function company(): BelongsTo
  {
    return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
  }
}
