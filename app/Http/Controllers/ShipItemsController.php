<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Item;
use App\Models\Order;
use App\Services\Financials\BalanceCalculator;
use Illuminate\Http\Request;

class ShipItemsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'carton_code' => 'required_if:order.type,buy_ship',
      'item' => 'required_if:order.type,buy_ship',
      'carton_quantity' => 'required|numeric',
      'pieces_number' => 'required_if:order.type,buy_ship|numeric',
      'single_price' => 'required_if:order.type,buy_ship|numeric',
      'total' => 'required_if:order.type,buy_ship',
      'check_date' => 'required_if:status,requested|required_if:order.type,buy_ship',
      'cbm' => 'required|numeric',
      'weight' => 'required|numeric',
      'status' => 'required',
      'supplier' => 'required_if:order.type,buy_ship|string',
      'phone' => 'required_if:order.type,buy_ship|numeric',
      'store_number' => 'required_if:order.type,buy_ship|numeric',
      'check_image' => 'required_if:order.type,buy_ship',
      'container_number' => 'required_if:status,shipped',
      'check_notes' => 'required_if:order.type,buy_ship',
      'receive_notes' => 'required_if:order.type,buy_ship',
      'cancelled_notes' => 'required_if:status,cancelled',
      'notes' => 'required_if:status,received|required_if:order.type,ship',
      'order_id' => 'required',
      'receive_date' => 'required_if:order.type,buy_ship'
    ]);
    $item = Item::updateOrCreate([
      'id' => $request->id
    ], [
      'carton_quantity' => $request->carton_quantity,
      'cbm' => $request->cbm,
      'weight' => $request->weight,
      'status' => $request->status,
      'container_number' => $request->container_number,
      'order_id' => $request->order_id,
      'receive_date' => ($request->receive_date == null && $request->status == 'received') ? now() : null,
      'notes' => $request->notes
    ]);
    if (app()->getLocale() == 'en') {
      return response()->json(['success' => 'Item Saved Successfully', 'data' => $item]);
    } else {
      return response()->json(['success' => 'تم حفظ البند بنجاح', 'data' => $item]);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $item = Item::find($id);
    if ($item != null) {
      $item->delete();
    }
    if (app()->getLocale() == 'en') {
      return response()->json(['success' => 'Item Deleted Successfully', 'data' => $item]);
    } else {
      return response()->json(['success' => 'تم حذف البند بنجاح', 'data' => $item]);
    }
  }
}
