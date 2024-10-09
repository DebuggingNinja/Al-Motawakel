<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Repository extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'address',
    'code',
  ];
  public function orders(): HasMany
  {
    return $this->hasMany(Order::class, 'repository_id');
  }
  public function items(): HasMany
  {
    return $this->hasMany(Item::class, 'repository_id');
  }
}
