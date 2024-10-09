<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyGenerator extends Model
{
  use HasFactory;

  protected $fillable = [
    'model_name',
    'prefix',
    'current_count',
  ];

  public static function Code($modelName)
  {
    $currentPrefex  = Setting::where('key', 'year')->first()?->value ?? 'NAN';
    $latestRecord   = KeyGenerator::firstOrCreate(
      ['model_name' => $modelName, 'prefix' =>  $currentPrefex],
      ['current_count' => 0]
    );
    $latestRecord->update(['current_count' => $latestRecord->current_count + 1]);
    return $currentPrefex . '-' . $latestRecord->current_count;
  }
}
