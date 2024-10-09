<?php

namespace App\Services\Suppliers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SuppliersService
{

  public function store($request)
  {
    $request->validate([
      'name' => 'required',
      'code' => 'required|unique:suppliers,code',
      'phone' => 'nullable|unique:suppliers',
      'store_number' => 'nullable'
    ]);
    Supplier::create([
      'name' => $request->name,
      'code' => $request->code,
      'phone' => $request->phone,
      'store_number' => $request->store_number
    ]);
  }
}
