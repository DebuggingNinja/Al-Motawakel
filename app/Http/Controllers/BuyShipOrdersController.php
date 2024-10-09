<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Item;
use App\Models\KeyGenerator;
use App\Models\Order;
use App\Models\Product;
use App\Models\Repository;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Supplier;
use App\Services\Financials\ClientBalanceCalculator;
use App\Services\Financials\SupplierBalanceCalculator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\type;

class BuyShipOrdersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view buy ship orders')->only('index');
    $this->middleware('can:add buy ship orders')->only('create', 'store');
    $this->middleware('can:update buy ship orders')->only('update', 'edit');
    $this->middleware('can:delete buy ship orders')->only('destroy');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $per_page = session('pagination_per_page');
    $per_page = (!empty($per_page)) ? $per_page : 20;
    $page     = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $offset   = ($page * $per_page) - $per_page;

    $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::parse("- 1 month");
    $end = $request->end_time ? Carbon::parse($request->end_time) : now();

    $orders = Order::with(['items', 'items.product'])->whereDate('created_at', '>=', $start)
      ->whereDate('created_at', '<=', $end);

    if($request->search){
      $orders->whereHas('client', function (Builder $query) use ($request) {
        if (($term = $request->search)) {
          $query->where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('code', 'LIKE', '%' . $term . '%');;
        }
      })->orWhereHas('supplier', function (Builder $query) use ($request) {
        if (($term = $request->search)) {
          $query->where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('code', 'LIKE', '%' . $term . '%');;
        }
      })->orWhereHas('repo', function (Builder $query) use ($request) {
        if (($term = $request->search)) {
          $query->where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('code', 'LIKE', '%' . $term . '%');
        }
      });
    }
    if($request->client){
      $orders->where('client_id', $request->client);
    }

    if($request->status){
      $orders->whereHas('items', function ($q) use($request){
        if($request->status == "received") $q->whereIn('status', ['shipped', 'received']);
        else $q->where('status', $request->status);
      });
    }

    $orders = $orders->latest()->paginate($per_page);

    $clients = Client::all(['id', 'name', 'code']);
    $title = "Orders";
    $description = "Show Orders";
    return view('buy_ship_orders.index', compact('title', 'clients', 'description', 'orders'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create New Order";
    $description = "Create New Order Page";
    $clients = Client::all(['id', 'name', 'code']);
    $suppliers = Supplier::all(['id', 'name', 'code']);
    $repos = Repository::all(['id', 'name', 'code']);
    $countRows = Order::count();

    $file = $countRows > 0 ? ceil($countRows / 30) : 1;

    return view('buy_ship_orders.create', compact('title', 'description', 'repos', 'clients', 'suppliers', 'file'));
  }

  public function sale(Request $request)
  {
    $title = "Sale Items";
    $description = "Sale Items Page";
    $order = Order::with(['items' => function ($q){
      $q->where(function ($w){
        $w->whereNull('sold')->orWhereRaw('sold < dozen_quantity');
      })->whereIn('status', ['shipped', 'received']);
    }])->findOrFail($request->order);
    $clients = Client::all(['id', 'name', 'code']);
    return view('buy_ship_orders.sale', compact('title', 'description', 'order', 'clients'));
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
      'repo' => 'required',
      'supplier' => 'required|exists:suppliers,id',
      'client' => 'required|exists:clients,id',
      'check_date' => 'required|date',
      'dollar_price' => 'required|numeric',
    ]);
    $client = Client::find($request->client);
    $supplier = Supplier::find($request->supplier);
    $order = Order::create([
      'repository_id' => $request->repo,
      'client_id' => $request->client,
      'supplier_id' => $request->supplier,
      'check_date' => $request->check_date,
      'dollar_price' => $request->dollar_price,
      'client_account_id' => $client->currentAccountID(),
      'supplier_account_id' => $supplier->currentAccountID(),
    ]);
    if($client->currentAccountID()) ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
    if($supplier->currentAccountID()) SupplierBalanceCalculator::init($supplier->currentAccountID())->calculate()->save();
    $order->generateBuyShipSerial();
    if (app()->getLocale() == 'en') {
      return redirect()->route('buy_ship_orders.edit', $order->id)->with('success', 'Buy Ship Order Created Successfully');
    } else {
      return redirect()->route('buy_ship_orders.edit', $order->id)->with('success', 'تم الحفظ');
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

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
    $title         = 'Edit Order';
    $description   = 'Edit Order Page';
    $order = Order::findOrFail($id);
    $clients = Client::all();
    $repos = Repository::all();
    $suppliers = Supplier::all();
    $products = Product::all();
    $fileBanner = Setting::where('key', 'print_banner')->first()?->value ?? '';
    return view('buy_ship_orders.edit', compact('title', 'description', 'order', 'products', 'repos', 'clients', 'suppliers', 'fileBanner'));
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
    $request->validate([
      'repo' => 'required',
      'supplier' => 'required|exists:suppliers,id',
      'client' => 'required|exists:clients,id',
      'check_date' => 'required|date',
      'dollar_price' => 'required|numeric',
    ]);
    $client = Client::find($request->client);
    $supplier = Supplier::find($request->supplier);
    $order = Order::findOrFail($id);
    if($order->check_date != $request->check_date){
      $order->items()->update([
        'check_date' => $request->check_date
      ]);
    }
    $order->update([
      'repository_id' => $request->repo,
      'client_id' => $request->client,
      'supplier_id' => $request->supplier,
      'check_date' => $request->check_date,
      'dollar_price' => $request->dollar_price,
      'client_account_id' => $client->currentAccountID(),
      'supplier_account_id' => $supplier->currentAccountID(),
    ]);
    if(!$order->code) $order->generateBuyShipSerial();
    if($client->currentAccountID()) ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
    if($supplier->currentAccountID()) SupplierBalanceCalculator::init($supplier->currentAccountID())->calculate()->save();
    if (app()->getLocale() == 'en') {
      return redirect()->route('buy_ship_orders.edit', $order->id)->with('update', 'Order Updated Successfully');
    } else {
      return redirect()->route('buy_ship_orders.edit', $order->id)->with('success', 'تم التعديل');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $find_order = Order::with(['client', 'supplier'])->findOrFail($id);
    $client = $find_order->client;
    $supplier = $find_order->supplier;
    $find_order->delete();
    if($client->currentAccountID()) ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
    if($supplier->currentAccountID()) SupplierBalanceCalculator::init($supplier->currentAccountID())->calculate()->save();
    if (app()->getLocale() == 'en') {
      return redirect()->route('buy_ship_orders.index')->with('success', 'Order Deleted Successfully');
    } else {
      return redirect()->route('buy_ship_orders.index')->with('success', 'تم الحذف');
    }
  }
}
