<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Container extends Model
{

  use HasFactory;
  protected $fillable = [
    'number',
    'lock_number',
    'shipping_date',
    'est_arrive_date',
    'destination',
    'shipping_type',
    'shipping_company_id',
    'broker_id',
    'repository_id',
    'serial_number',
    'cost_rmb',
    'cost_dollar',
    'commission',
    'back_rmb',
    'client_account_id',
    'company_account_id',
    'client_id',
    'dollar_price',
  ];

  public function clientAccount(): BelongsTo
  {
    return $this->belongsTo(Account::class, 'client_account_id');
  }

  public function companyAccount(): BelongsTo
  {
    return $this->belongsTo(Account::class, 'company_account_id');
  }

  public function items(): HasMany
  {
    return $this->hasMany(ContainerItem::class, 'container_id');
  }
  public function company(): BelongsTo
  {
    return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
  }
  public function client(): BelongsTo
  {
    return $this->belongsTo(Client::class, 'client_id');
  }
  public function clients(): HasMany
  {
    return $this->hasMany(ContainerClient::class, 'container_id');
  }
  public function broker(): BelongsTo
  {
    return $this->belongsTo(Broker::class, 'broker_id');
  }
  public function repo(): BelongsTo
  {
    return $this->belongsTo(Repository::class, 'repository_id');
  }

  public function generateSerial(){
    $year = Setting::where('key', 'year')->value('value');
    $start = self::where('serial_number', 'LIKE', "%C-$year%")->count() + 1;
    $serial = "C-" . $year . "-" . $start;
    while(self::where('serial_number', $serial)->first()) $serial = "C-" . $year . "-" . ++$start;
    $this->serial_number = $serial;
    $this->save();
    $this->refresh();
  }

  protected static function boot()
  {
    parent::boot();
    static::deleting(function ($container) {
      DB::transaction(function () use ($container) {
        foreach ($container->items as $containerItem) {
          if ($containerItem != null) {
            $containerItem->delete();
          }
        }
      });
    });
  }
}
