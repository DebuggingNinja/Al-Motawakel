<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
  use HasFactory;

  protected $fillable = [
    'client_id',
    'item_id',
    'quantity',
    'dozen_quantity',
    'price',
    'dozen_price',
    'cbm',
  ];

  public function client(): BelongsTo
  {
    return $this->belongsTo(Client::class);
  }

  public function item(): BelongsTo
  {
    return $this->belongsTo(Item::class);
  }

}
