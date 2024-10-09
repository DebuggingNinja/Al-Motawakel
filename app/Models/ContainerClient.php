<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ContainerClient extends Model
{
  use HasFactory;

  protected $fillable = [
    'meter_price',
    'total',
    'quantity',
    'notes',
    'container_id',
    'client_id',
    'collect_by',
  ];

  public function container(): BelongsTo
  {
    return $this->belongsTo(Container::class, 'container_id');
  }
  public function client(): BelongsTo
  {
    return $this->belongsTo(Client::class, 'client_id');
  }
}
