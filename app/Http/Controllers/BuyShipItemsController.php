<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\Financials\BalanceCalculator;
use App\Services\Financials\ClientBalanceCalculator;
use App\Services\Financials\SupplierBalanceCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BuyShipItemsController extends Controller
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
      'order' => 'required|exists:orders,id',
      'product' => 'required|exists:products,id',
      'carton_quantity' => 'required|numeric',
      'single_price' => 'required|numeric',
      'measuring' => 'sometimes|nullable',
    ]);
    $order = Order::find($request->order);
    $product = Product::find($request->product);
    $item = $order->items()->create([
      'product_id' => $product->id,
      'pieces_number' => $product->pieces_number,
      'total' => $request->single_price * ($request->carton_quantity * $product->pieces_number),
      'weight' => $product->weight * $request->carton_quantity,
      'check_date' => $order->check_date,
      'cbm' => number_format($product->cbm * $request->carton_quantity, 2),
      'dozen_quantity' => intdiv(($request->carton_quantity * $product->pieces_number), 12),
      'carton_quantity' => $request->carton_quantity,
      'single_price' => $request->single_price,
      'status' => 'requested',
      'measuring' => $product->measuring,
      'repository_id' => $order->repository_id
    ]);
    if (app()->getLocale() == 'en') {
      return response()->json(['success' => 'Item Updated Successfully', 'data' => $item]);
    } else {
      return response()->json(['success' => 'تم تعديل البند بنجاح', 'data' => $item]);
    }
  }

  public function update(Request $request)
  {
    $request->validate([
      'product' => 'required|exists:products,id',
      'carton_quantity' => 'required|numeric',
      'pieces_number' => 'sometimes|numeric',
      'single_price' => 'required|numeric',
      'dozen_price' => 'sometimes|nullable|numeric',
      'total' => 'sometimes|numeric',
      'cbm' => 'required|numeric',
      'weight' => 'required|numeric',
      'status' => 'required',
      'check_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
      'container_number' => 'required_if:status,shipped',
      'check_notes' => 'sometimes',
      'receive_notes' => 'sometimes',
      'cancelled_notes' => 'sometimes',
      'measuring' => 'sometimes',
      'order_id' => 'required|exists:orders,id',
      'receive_date' => 'required_if:status,waiting',
    ]);
    $imageName = null;
    if ($request->hasFile('check_image')) {
      $image = $request->file('check_image');
      $imageName = $request->id . '_' . time() . '.' . $image->getClientOriginalExtension();
      $image->storeAs('public/check_images', $imageName);
    }
    $receiveDate = ($request->receive_date == null && $request->status == 'received') ? now() : null;
    if($request->status == 'waiting') $receiveDate = $request->receive_date;
    $order = Order::with(['client', 'supplier'])->find($request->order_id);
    $product = Product::find($request->product);
    $item = Item::find($request->id);
    $repo = $order->repository_id;
    if($item && $item->repository_id) $repo = $item->repository_id;
    $item = Item::updateOrCreate([
      'id' => $request->id
    ], [
      'product_id' => $product->id,
      'pieces_number' => $product->pieces_number,
      'total' => $request->total ?? ($request->single_price * ($request->carton_quantity * $product->pieces_number)),
      'weight' => $request->weight ?? ($product->weight * $request->carton_quantity),
      'check_date' => $order->check_date,
      'cbm' => $request->cbm ?? number_format($product->cbm * $request->carton_quantity, 2),
      'dozen_quantity' => (($request->carton_quantity * $product->pieces_number) ?? 12) / 12,
      'item' => $request->item,
      'carton_quantity' => $request->carton_quantity,
      'single_price' => $request->single_price,
      'dozen_price' => $request->dozen_price,
      'status' => $request->status,
      'check_image' => $imageName,
      'container_number' => $request->container_number,
      'check_notes' => $request->check_notes,
      'receive_notes' => $request->receive_notes,
      'cancelled_notes' => $request->cancelled_notes,
      'receive_date' => $receiveDate,
      'measuring' => $product->measuring,
      'repository_id' => $repo
    ]);
    $item_ = Item::with('order')->find($request->id ?? $item);
    $client = $order->client;
    $supplier = $order->supplier;
    if($client->currentAccountID()) ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
    if($supplier->currentAccountID()) SupplierBalanceCalculator::init($supplier->currentAccountID())->calculate()->save();
    if (app()->getLocale() == 'en') {
      return response()->json(['success' => 'Item Saved Successfully', 'data' => $item]);
    } else {
      return response()->json(['success' => 'تم حفظ البند بنجاح', 'data' => $item]);
    }
  }

  public function update_bulk(Request $request)
  {
    $request->validate([
      'items' => 'required|array',
      'items.*' => 'required|exists:items,id',
      'status' => 'required',
      'check_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
      'check_notes' => 'sometimes',
      'receive_notes' => 'sometimes',
      'cancelled_notes' => 'sometimes',
      'receive_date' => 'required_if:status,waiting',
    ]);
    $imageName = null;
    if ($request->hasFile('check_image')) {
      $image = $request->file('check_image');
      $imageName = $request->id . '_' . time() . '.' . $image->getClientOriginalExtension();
      $image->storeAs('public/check_images', $imageName);
    }
    $receiveDate = ($request->receive_date == null && $request->status == 'received') ? now() : null;
    if($request->status == 'waiting') $receiveDate = $request->receive_date;
    $item = Item::with(['order.supplier', 'order.client'])->whereIn('id', $request->items);
    $item->update([
      'status' => $request->status,
      'check_image' => $imageName,
      'check_notes' => $request->check_notes,
      'receive_notes' => $request->receive_notes,
      'cancelled_notes' => $request->cancelled_notes,
      'receive_date' => $receiveDate,
    ]);
    $client = $item->first()?->order?->client;
    $supplier = $item->first()?->order?->supplier;
    if($client?->currentAccountID()) ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
    if($supplier?->currentAccountID()) SupplierBalanceCalculator::init($supplier->currentAccountID())->calculate()->save();
    if (app()->getLocale() == 'en') {
      return response()->json(['success' => 'Items Updated Successfully', 'data' => $item]);
    } else {
      return response()->json(['success' => 'تم تعديل البنود بنجاح', 'data' => $item]);
    }
  }

  public function copy(Request $request){
    $request->validate([
      'item' => 'required|exists:items,id',
      'count' => 'required|numeric|min:1'
    ]);

    try {
      DB::beginTransaction();
      $item = Item::findOrFail($request->item);
      for ($i = 1; $i <= $request->count; $i++){
        $new = $item->replicate();
        $new->created_at = now();
        $new->save();
      }
      DB::commit();
    }catch (\Exception $ex){
      DB::rollBack();
      return response()->json(['error' => true, 'message' => 'failed to copy items']);
    }

    return response()->json(['error' => false, 'message' => 'items coped']);
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
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    if(is_array($request->items)) {
      $items = Item::with(['order.client', 'order.supplier'])->whereIn('id', $request->items);
      $client = $items->first()?->order?->client;
      $supplier = $items->first()?->order?->supplier;
    }else {
      $items = Item::with(['order.client', 'order.supplier'])->find($request->items);
      $client = $items->order?->client;
      $supplier = $items->order?->supplier;
    }
    $items?->delete();
    if($client?->currentAccountID()) ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
    if($supplier?->currentAccountID()) SupplierBalanceCalculator::init($supplier->currentAccountID())->calculate()->save();
    if (app()->getLocale() == 'en') {
      return response()->json(['success' => 'Item Deleted Successfully']);
    } else {
      return response()->json(['success' => 'تم حذف البند بنجاح']);
    }
  }
}
