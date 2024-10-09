<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Container;
use App\Models\Item;
use App\Models\Order;
use App\Models\Repository;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CurrentReopositoriesController extends Controller
{
  public function index()
  {
    $title = "Repository";
    $description = "Current Items in The Repository";
    $repos = Repository::all(['id', 'name', 'code']);
    return view('repository.index', compact('title', 'description', 'repos'));
  }
  public function currentItems(Request $request)
  {
    if(!$request->repo_id) return $this->index();
    $error = null;
    if($request->has('distribute_items')) $error = $this->createSale($request);
    if($request->has('move_items')) $error = $this->moveItems($request);
    $per_page = session('pagination_per_page');
    $per_page = (!empty($per_page)) ? $per_page : 20;
    $page     = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $repo = Repository::with(['orders.items', 'orders.items.product', 'orders.client'])->findOrFail($request->repo_id);
    if($request->search){
      $items = $repo->items()->where(function ($query) use($request){
          $query->whereHas('order', function ($q) use($request){
            $q->whereHas('client', function ($query) use($request){
              $query->where('name', 'LIKE', "%$request->search%")
                ->orWhere('code', $request->search);
            });
          })->orWhereHas('product', fn($query) => $query->where('name', 'LIKE', "%$request->search%"));
      });
      if($request->showShipped) {
        $items->whereDate('shipping_date', '>=', $request->start_date)
          ->whereDate('shipping_date', '<=', $request->end_date);
      }
      $itemsWithStatusReceived = $items;
    }else{
      if($request->showShipped){
        $itemsWithStatusReceived = $repo->items()->whereDate('shipping_date', '>=', $request->start_date)
          ->whereDate('shipping_date', '<=', $request->end_date);
      }else $itemsWithStatusReceived = $repo->items();
    }

    $itemsWithStatusReceived = $request?->showShipped ? $itemsWithStatusReceived->where('status', 'shipped') :
      $itemsWithStatusReceived->where('status', 'received');
    $itemsWithStatusReceived = $itemsWithStatusReceived->get();

    $currentPageItems = $itemsWithStatusReceived->slice(($page - 1) * $per_page, $per_page)->all();
    $items = new LengthAwarePaginator(
      $currentPageItems,
      count($itemsWithStatusReceived),
      $per_page,
      $page,
      ['path' => url()->current()]
    );
    $containers = Container::where([['number', '!=', Null]])->latest()->get();

    $sales = Sale::whereIn('item_id', $items->pluck('id')->toArray())->get();

    $clients = Client::all(['id', 'name', 'code']);
    $title = "Repository";
    $description = "Current Items in The Repository";
    $repos = Repository::all(['id', 'name', 'code']);
    $fileBanner = Setting::where('key', 'print_banner')->first()?->value ?? '';
    return view('repository.currentItems', compact('title', 'description', 'clients', 'items', 'sales', 'repo', 'repos', 'containers', 'error', 'fileBanner'));
  }

  public function createSale(Request $request)
  {

    $client = Client::find($request->client);

    if(!$client){
      if (app()->getLocale() == 'en') {
        return 'Invalid Client';
      } else {
        return 'من فضلك حدد العميل';
      }
    }

    $order = 0;
    try {
      DB::beginTransaction();
      foreach ($request->items as $item){
        $itemQuantity = $request?->{"quantity_" . $item};
        $realItem = Item::find($item);
        $rate = $itemQuantity / ($realItem->carton_quantity / 100);
        if(!$realItem){
          if (app()->getLocale() == 'en') {
            return 'Invalid Item';
          } else {
            return 'من فضلك حدد العناصر';
          }
        }
        if(!$itemQuantity || $itemQuantity > ($realItem->carton_quantity - intval($realItem->sold))){
          if (app()->getLocale() == 'en') {
            return 'Invalid Quantity For ' . $realItem->product?->name;
          } else {
            return $realItem->product?->name . ' كمية غير صحيحة للعنصر ';
          }
        }

        $realItem->sales()->create([
          'client_id' => $client->id,
          'quantity' => $itemQuantity,
          'cbm' => number_format(($realItem->cbm / 100) * $rate, 2),
          'price' => $realItem->single_price,
        ]);

        $realItem->update([
          'sold' => $realItem->sold + $itemQuantity
        ]);

      }
      DB::commit();
    }catch (\Exception $ex){
      DB::rollBack();
    }

    return true;

  }

  public function moveItems(Request $request)
  {

    $repo = Repository::find($request->repo);

    if(!$repo){
      if (app()->getLocale() == 'en') {
        return 'Invalid Repository';
      } else {
        return 'من فضلك حدد المستودع';
      }
    }

    $order = 0;
    try {
      DB::beginTransaction();
      foreach ($request->items as $item){
        $itemQuantity = $request?->{"quantity_" . $item};
        $realItem = Item::find($item);
        if(!$realItem){
          if (app()->getLocale() == 'en') {
            return 'Invalid Item';
          } else {
            return 'من فضلك حدد العناصر';
          }
        }
        if($itemQuantity > $realItem->carton_quantity){
          if (app()->getLocale() == 'en') {
            return 'Invalid Quantity';
          } else {
            return 'كمية غير صحيحة';
          }
        }
        if($itemQuantity == $realItem->carton_quantity){
          $realItem->update(['repository_id' => $repo->id]);
        }else{
          $rate = $itemQuantity / ($realItem->carton_quantity / 100);
          $newItem = $realItem->replicate();
          $newItem->save();
          $newCBM = number_format(($newItem->cbm / 100) * $rate, 2);
          $newWeight = number_format(($newItem->weight / 100) * $rate, 2);
          $newTotal = ($newItem->carton_quantity * $newItem->pieces_number) * $newItem->single_price;
          $newItem->update([
            'carton_quantity' => $itemQuantity,
            'repository_id' => $repo->id,
            'cbm' => $newCBM,
            'weight' => $newWeight,
            'total' => $newTotal
          ]);

          $realItem->update([
            'carton_quantity' => $realItem->carton_quantity - $itemQuantity,
            'cbm' => $realItem->cbm - $newCBM,
            'weight' => $realItem->weight - $newWeight,
            'total' => $realItem->total - $newTotal
          ]);
        }
      }
      DB::commit();
    }catch (\Exception $ex){
      DB::rollBack();
    }

    return true;

  }

  public function updateQuantity(Request $request){
    $request->validate([
      'quantity' => 'required|numeric|min:1',
      'price' => 'required|numeric|min:1',
      'item' => 'required|exists:sales,id'
    ]);

    $itemQuantity = $request->quantity;
    $itemPrice = $request->price;
    $item = Sale::findOrFail($request->item);
    if($itemQuantity == $item->quantity && $itemPrice == $item->single_price){
      return response()->json(['error' => true, 'message' => 'same values']);
    }
    $realItem = $item->item;
    if($itemQuantity > $item->quantity &&
        ($itemQuantity - $item->quantity) > ($realItem->carton_quantity - $realItem->sold)){
      return response()->json(['error' => true, 'message' => 'quantity not available']);
    }
    try {
      DB::beginTransaction();
      $rate = $itemQuantity / ($realItem->carton_quantity / 100);
      $realItem->sales()->update([
        'quantity' => $itemQuantity,
        'cbm' => number_format(($realItem->cbm / 100) * $rate, 2),
      ]);
      if($itemQuantity > $item->quantity){
        $realItem->update([
          'sold' => $realItem->sold + ($itemQuantity - $item->quantity)
        ]);
      }else{
        $realItem->update([
          'sold' => $realItem->sold - ($item->quantity - $itemQuantity)
        ]);
      }
      DB::commit();
    }catch (\Exception $ex){
      DB::rollBack();
      return response()->json(['error' => true, 'message' => 'failed to update quantity']);
    }

    return response()->json(['error' => false, 'message' => 'quantity updated']);
  }

  public function updateNotes(Request $request){
    $request->validate([
      'note' => 'required|min:3',
      'item' => 'required|exists:items,id'
    ]);

    $item = Item::find($request->item);

    return !$item->update(['shipping_notes' => $request->note]) ?
      response()->json(['error' => true, 'message' => 'failed to update notes']) :
      response()->json(['error' => false, 'message' => 'notes updated']);
  }

  public function ship(Request $request){
    $request->validate([
      'container' => 'required|exists:containers,id',
      'items' => 'required|array',
      'items.*' => 'required|exists:items,id'
    ]);
    $container = Container::find($request->container);
    $items = Item::with(['order']);
    if($container?->client_id){
      $items->whereIn('id', $request->items)->whereHas('order', fn($q) => $q->where('client_id', $container->client_id));
      if($items->count() != count($request->items)){
        return app()->getLocale() == 'en' ?
          response()->json(['error' => true, 'message' => 'some of the shipped items dont belong to the container client']):
          response()->json(['error' => true, 'message' => 'بعض العناصر المشحونة لاتنتمى للعميل المسجل فى الحاوية']);
      }
    }
    $item =  $items->update([
      'container_number' => $container->number,
      'shipping_date' => now(),
      'status' => 'shipped'
    ]);
    $now = now();
    $container->items()->insert(array_map(function ($item) use($now, $container){
      $total = $item['total'] / ($item['order']['dollar_price'] ?? 1);
      return [
        'total' => $container->commision ? ($total + (($total / 100) * $container->commision)) : $total,
        'item_id' => $item['id'],
        'container_id' => $container->id,
        'created_at' => $now,
        'updated_at' => $now,
      ];
    }, $items->get()->toArray()));
    if(!$item) return response()->json(['error' => true, 'message' => 'failed to ship items']);

    if (app()->getLocale() == 'en') {
      return response()->json(['error' => false, 'message' => 'items shipped']);
    } else {
      return response()->json(['error' => false, 'message' => 'تم الشحن']);
    }
  }

  public function confirmSale(Request $request){
    $request->validate([
      'items' => 'required|array',
      'items.*' => 'required|exists:sales,id'
    ]);

    $orders = [];

    try {
      DB::beginTransaction();
      foreach ($request->items as $item){
        $sale = Sale::with(['client', 'item', 'item.order', 'item.order.supplierAccount'])->find($item);
        $item = $sale->item;
        $new = $item->replicate();
        $new->created_at = now();
        $new->save();
        $cbmRate = $item->carton_quantity / 100;
        $cbmRate = $sale->quantity / $cbmRate;
        $cartonQTY = $sale->quantity;

        if(!isset($orders[$sale->client_id])){
          $order = Order::create([
            'repository_id' => $item->order->repository_id,
            'supplier_id' => $item->order->supplier_id,
            'check_date' => $item->order->check_date,
            'client_id' => $sale->client_id,
            'dollar_price' => $item->order->dollar_price,
            'client_account_id' => $sale->client?->currentAccountID() ?? null,
            'supplier_account_id' => $item->order?->supplierAccount?->id ?? null,
          ]);
          $order->generateBuyShipSerial();
          $orders[$sale->client_id] = $order;
        }else{
          $order = $orders[$sale->client_id];
        }

        $new->update([
            'cbm' => $sale->cbm,
            'order_id' => $order->id,
            'price' => $sale->price,
            'weight' => number_format(($item->weight / 100) * $cbmRate, 2),
            'carton_quantity' => $cartonQTY,
            'total' => ($item->pieces_number * $cartonQTY) * ($sale->price),
            'sold' => 0,
        ]);
        $new->refresh();
        if($item->carton_quantity > $new->carton_quantity){
          $item->update([
            'cbm' => $item->cbm - $new->cbm,
            'weight' => $item->weight - $new->weight,
            'carton_quantity' => $item->carton_quantity - $new->carton_quantity,
            'total' => $item->total - $new->total,
            'sold' => $item->sold - $sale->quantity,
          ]);
        }else $item->delete();
        $sale->delete();
      }
      DB::commit();
    }catch (\Exception $ex){
      DB::rollBack();
      return response()->json(['error' => true, 'message' => 'failed to distribute items']);
    }

    return response()->json(['error' => false, 'message' => 'items distributed']);
  }

  public function declineSale(Request $request){
    $request->validate([
      'items' => 'required|array',
      'items.*' => 'required|exists:sales,id'
    ]);

    try {
      DB::beginTransaction();
      foreach ($request->items as $item){
        $sale = Sale::find($item);
        $item = $sale->item;
        $item->update([
            'sold' => $item->sold - $sale->quantity,
        ]);
        $sale->delete();
      }
      DB::commit();
    }catch (\Exception $ex){
      DB::rollBack();
      return response()->json(['error' => true, 'message' => 'failed to cancel']);
    }

    return response()->json(['error' => false, 'message' => 'items cancelled']);
  }
}
