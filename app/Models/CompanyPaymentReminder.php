<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPaymentReminder extends Model
{
  use HasFactory;

  protected $fillable = ['shipping_company_id', 'description', 'payment_date', 'status'];

  public function company()
  {
    return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
  }
  protected static function booted()
  {
    static::retrieved(function ($model) {
      // Check if the payment date has passed and status is pending
      if ($model->status === 'pending' && Carbon::now()->gt(Carbon::parse($model->payment_date)->addDay(1))) {
        // Update the status to "overdue"
        $model->update(['status' => 'overdue']);
      }
      if ($model->status === 'overdue' && Carbon::now()->lte(Carbon::parse($model->payment_date)->addDay(1))) {
        // Update the status to "pending"
        $model->update(['status' => 'pending']);
      }

      if (((float) $model->company?->activeAccount?->total_dollar ?? 0) < 1
        && ((float) $model->company?->activeAccount?->total_rmb ?? 0) < 1) {
        $model->update(['status' => 'paid']);
      }
    });
  }
}
