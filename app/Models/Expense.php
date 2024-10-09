<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
  use HasFactory;
  protected $fillable = [
    'description',
    'amount',
    'rate',
    'currency',
    'date',
    'client_id',
    'type',
    'supplier_id',
    'shipping_company_id',
  ];

  public function client(): BelongsTo
  {
    return $this->belongsTo(Client::class);
  }
  public function supplier(): BelongsTo
  {
    return $this->belongsTo(Supplier::class);
  }
  public function company(): BelongsTo
  {
    return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
  }
}
