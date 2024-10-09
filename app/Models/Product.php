<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
  use HasFactory;
  protected $fillable = [
    'code',
    'name',
    'pieces_number',
    'cbm',
    'weight',
    'image',
    'measuring',
  ];

  public function items(): HasMany
  {
    return $this->hasMany(Item::class);
  }

}
