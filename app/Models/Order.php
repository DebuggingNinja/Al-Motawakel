<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
  use HasFactory;
  protected $fillable = [
    'code',
    'file',
    'registery',
    'paper',
    'repository_id',
    'client_id',
    'supplier_id',
    'commission',
    'check_date',
    'dollar_price',
    'client_account_id',
    'supplier_account_id'
  ];

  public function clientAccount(): BelongsTo
  {
    return $this->belongsTo(Account::class, 'client_account_id');
  }

  public function supplierAccount(): BelongsTo
  {
    return $this->belongsTo(Account::class, 'supplier_account_id');
  }

  public function supplier(): BelongsTo
  {
    return $this->belongsTo(Supplier::class, 'supplier_id');
  }

  public function items(): HasMany
  {
    return $this->hasMany(Item::class, 'order_id');
  }
  public function client(): BelongsTo
  {
    return $this->belongsTo(Client::class, 'client_id');
  }

  public function repo(): BelongsTo
  {
    return $this->belongsTo(Repository::class, 'repository_id');
  }

  public function containerItems()
  {
    return $this->hasManyThrough(ContainerItem::class, Item::class, '');
  }
  private function calculateTotalFromItems()
  {
    return $this->items()->sum('total');
  }
  private function calculateTotalFromContainerItems()
  {
    return $this->items->sum(function ($item) {
      return optional($item->containerItem)->total ?? 0;
    });
  }

  public function generateBuyShipSerial(){
    $year = Setting::where('key', 'year')->value('value');
    $lastRegistry = self::where('code', 'LIKE', "%{$year}-%")
      ->orderBy('registery', 'desc')->first()?->registery ?? 1;
    $lastPaper = self::where('code', 'LIKE', "%{$year}-%")
      ->where('registery', $lastRegistry)->orderBy('paper', 'desc')->first()?->paper ?? 0;
    $lastPaper++;
    if($lastPaper > 30){
      $lastRegistry++;
      $lastPaper = 1;
    }
    $serial = $year . "-" . $lastRegistry . "-" . $lastPaper;
    while(self::where('code', $serial)->first()){
      if($lastPaper > 30){
        $lastRegistry++;
        $lastPaper = 0;
      }
      $serial = $year . "-" . $lastRegistry . "-" . ++$lastPaper;
    }
    $this->code = $serial;
    $this->registery = $lastRegistry;
    $this->paper = $lastPaper;
    $this->save();
    $this->refresh();
  }

  public function generateShipSerial(){
    $this->generateBuyShipSerial();
  }

}
