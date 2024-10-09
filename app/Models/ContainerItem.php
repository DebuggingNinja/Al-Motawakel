<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ContainerItem extends Model
{
  use HasFactory;

  protected $fillable = [
    'total',
    'notes',
    'item_id',
    'container_id',
    'is_sold',
  ];

  protected $casts = [
    'is_sold' => 'boolean'
  ];

  public function container(): BelongsTo
  {
    return $this->belongsTo(Container::class, 'container_id');
  }
  public function item(): BelongsTo
  {
    return $this->belongsTo(Item::class, 'item_id');
  }
}
