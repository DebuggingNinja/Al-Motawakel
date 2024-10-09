<?php

namespace App\Models;

use App\Traits\AccountMethods;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    use HasFactory, AccountMethods;
    protected $fillable = [
        'name',
        'code',
        'phone',
        'phone2',
        'email',
        'address',
        'balance',
        'active_account_id'
    ];

  public function containers()
  {
    return $this->hasMany(Container::class, 'shipping_company_id');
  }

  public function ledgers()
  {
    return $this->hasMany(ShipperLedger::class, 'shipping_company_id');
  }

  public function reminder()
  {
    return $this->hasMany(CompanyPaymentReminder::class);
  }
}
