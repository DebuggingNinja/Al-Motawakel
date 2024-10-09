<?php

namespace App\Models;

use App\Services\Financials\BalanceCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
  use HasFactory;
  protected $fillable = [
    'carton_quantity',
    'pieces_number',
    'single_price',
    'dozen_price',
    'total',
    'check_date',
    'cbm',
    'weight',
    'status',
    'container_number',
    'check_notes',
    'receive_notes',
    'cancelled_notes',
    'shipping_notes',
    'notes',
    'receive_date',
    'shipping_date',
    'order_id',
    'product_id',
    'mark',
    'sold',
    'dozen_quantity',
    'measuring',
    'repository_id',
  ];

  protected $casts = [
    'shipping_date' => 'datetime',
  ];

  public function sales(): HasMany
  {
    return $this->hasMany(Sale::class);
  }

  public function repo(): BelongsTo
  {
    return $this->belongsTo(Repository::class, 'repository_id');
  }

  public function order(): BelongsTo
  {
    return $this->belongsTo(Order::class, 'order_id');
  }
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class, 'product_id');
  }
  public function containerItem(): HasOne
  {
    return $this->hasOne(ContainerItem::class);
  }
}
