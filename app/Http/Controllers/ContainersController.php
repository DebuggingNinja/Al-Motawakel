<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Models\Client;
use App\Models\Container;
use App\Models\ContainerItem;
use App\Models\Item;
use App\Models\Repository;
use App\Models\Setting;
use App\Models\ShippingCompany;
use App\Services\Financials\BalanceCalculator;
use App\Services\Financials\ClientBalanceCalculator;
use App\Services\Financials\SupplierBalanceCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContainersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view containers')->only('index');
    $this->middleware('can:add containers')->only('create', 'store');
    $this->middleware('can:update containers')->only('update', 'edit');
    $this->middleware('can:delete containers')->only('destroy');
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
    $containers = Container::with(['company', 'broker', 'repo'])->where([
      ['number', '!=', Null],
      [function ($query) use ($request) {
        if (($term = $request->search)) {
          $query->orWhere('number', 'LIKE', '%' . $term . '%')
            ->orWhere('lock_number', 'LIKE', '%' . $term . '%')
            ->get();
        }
      }]
    ])->latest()->paginate($per_page);
    $title = "Containers";
    $description = "Show Containers";
    return view('containers.index', compact('title', 'description', 'containers'));
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create new Container";
    $description = "Create New Container Page";
    $clients = Client::all(['id', 'name', 'code']);
    $brokers = Broker::all(['id', 'name', 'code']);
    $repos = Repository::all(['id', 'name', 'code']);
    $companies = ShippingCompany::all(['id', 'name']);
    return view('containers.create', compact('title', 'description', 'repos', 'companies', 'brokers', 'clients'));
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
      'number' => 'required|unique:containers',
      'lock_number' => 'required',
      'est_arrive_date' => 'required|date',
      'destination' => 'required',
      'shipping_type' => 'required|in:complete,partial',
      'company' => 'required|exists:shipping_companies,id',
      'client' => 'required|exists:clients,id',
      'broker' => 'required|exists:brokers,id',
      'repo' => 'required|exists:repositories,id',
      'cost_dollar' => 'nullable|sometimes|numeric',
      'cost_rmb' => 'nullable|sometimes|numeric',
      'back_rmb' => 'nullable|sometimes|numeric',
      'dollar_price' => 'required|numeric',
    ]);
    $client = Client::find($request->client);
    $company = ShippingCompany::find($request->company);
    $container = Container::create([
      'number' => $request->number,
      'lock_number' => $request->lock_number,
      'est_arrive_date' => $request->est_arrive_date,
      'destination' => $request->destination,
      'shipping_type' => $request->shipping_type,
      'shipping_company_id' => $request->company,
      'client_id' => $request->client,
      'broker_id' => $request->broker,
      'repository_id' => $request->repo,
      'cost_dollar' => $request->cost_dollar,
      'cost_rmb' => $request->cost_rmb,
      'back_rmb' => $request->back_rmb ?? 0,
      'dollar_price' => $request->dollar_price ?? 1,
      'client_account_id' => $client->currentAccountID(),
      'company_account_id' => $company->currentAccountID(),
    ]);
    if(!$container){
      if (app()->getLocale() == 'en') {
        return redirect()->route('containers.edit', $container)->withErrors(['error' => 'Container Creation Failed']);
      } else {
        return redirect()->route('containers.edit', $container)->withErrors(['error' => 'فشل الحفظ']);
      }
    }
    $container->generateSerial();
    if($company?->currentAccountID()) SupplierBalanceCalculator::init($company->currentAccountID())->calculate()->save();
    if (app()->getLocale() == 'en') {
      return redirect()->route('containers.edit', $container)->with(['success' => 'Container Created Successfully']);
    } else {
      return redirect()->route('containers.edit', $container)->with(['success' => 'تم الحفظ بنجاح']);
    }
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $title         = 'Edit Container';
    $description   = 'Edit Container Page';
    $container = Container::findOrFail($id);
    if(!$container->serial_number) $container->generateSerial();
    $brokers = Broker::all(['id', 'name', 'code']);
    $clients = Client::all(['id', 'name', 'code']);
    $repos = Repository::all(['id', 'name']);
    $companies = ShippingCompany::all(['id', 'name']);
    $collectedItems = Item::with(['containerItem', 'order', 'product', 'order.client'])->where('container_number', $container->number)->get();
    $items = [];
    foreach ($collectedItems as $item){
        if(!isset($items[$item->id])){
          $cl = $container->items->where('item_id', $item->id)->first();
          if(!$cl) $cl = $container->items()->create([
              'item_id' => $item->id,
              'total' => 0
          ]);
          if($cl->is_sold) continue;
          $items[$item->id] = (object) [
            "id" => $cl->id,
            "client" => $item->order->client,
            "order" => $item->order,
            "product" => $item,
            "item" => $cl,
            "weight" => 0,
            "cbm" => 0,
            "quantity" => 0
          ];
        }
      $items[$item->id]->weight = $item->weight;
      $items[$item->id]->cbm = $item->cbm;
      $items[$item->id]->quantity = $item->carton_quantity;
    }
    $fileBanner = Setting::where('key', 'print_banner')->first()?->value ?? '';

    return view('containers.edit', compact('title', 'description', 'container', 'brokers', 'clients', 'repos', 'companies', 'items', 'fileBanner'));
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
      'number' => 'required|unique:containers,id,' . $id,
      'lock_number' => 'required',
      'est_arrive_date' => 'required|date',
      'destination' => 'required',
      'shipping_type' => 'required|in:complete,partial',
      'company' => 'required|exists:shipping_companies,id',
      'client' => 'required|exists:clients,id',
      'broker' => 'required|exists:brokers,id',
      'repo' => 'required|exists:repositories,id',
      'cost_dollar' => 'nullable|sometimes|numeric',
      'cost_rmb' => 'nullable|sometimes|numeric',
      'back_rmb' => 'nullable|sometimes|numeric',
      'commission' => 'required|numeric|min:0|max:99',
      'dollar_price' => 'required|numeric',
    ]);
    $client = Client::find($request->client);
    $company = ShippingCompany::find($request->company);
    $container = Container::with(['items.item.order'])->findOrFail($id);
    $updateCommission = $request->commission && $request->commission != $container->commission;
    $container->update([
      'name' => $request->number,
      'lock_number' => $request->lock_number,
      'est_arrive_date' => $request->est_arrive_date,
      'destination' => $request->destination,
      'shipping_type' => $request->shipping_type,
      'shipping_company_id' => $request->company,
      'broker_id' => $request->broker,
      'client_id' => $request->client,
      'repository_id' => $request->repo,
      'cost_dollar' => $request->cost_dollar,
      'cost_rmb' => $request->cost_rmb,
      'back_rmb' => $request->back_rmb,
      'commission' => $request->commission,
      'dollar_price' => $request->dollar_price,
      'client_account_id' => $client->currentAccountID(),
      'company_account_id' => $company->currentAccountID(),
    ]);
    if($updateCommission){
      $container->items->map(function ($item) use ($request){
        $dozenQuantity = intval(($item->item->carton_quantity * $item->item->pieces_number) / 12);
        $dozenSinglePrice = (float) number_format(($item->item->single_price * 12) / ($item->item->order->dollar_price ?? 1), 3, '.', '');
        $commission = ($dozenSinglePrice / 100) * $request->commission;
        $singlePlusCommission = (float) number_format( $dozenSinglePrice + $commission, 3, '.', '');
        $totalPlusCommission = (float) number_format($singlePlusCommission * $dozenQuantity, 3, '.', '');
        $item->update([
          'total' => $totalPlusCommission
        ]);
      });
    }
    if($company?->currentAccountID()) SupplierBalanceCalculator::init($company->currentAccountID())->calculate()->save();
    if (app()->getLocale() == 'en') {
      return redirect()->route('containers.edit', $container)->with('success', 'Container Updated Successfully');
    } else {
      return redirect()->route('containers.edit', $container)->with('success', 'تم التعديل');
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
    $container = Container::with(['client', 'company'])->findOrFail($id);
    $company = $container?->company;
    $client = $container?->client;
    $container->delete();
    if($company?->currentAccountID()) SupplierBalanceCalculator::init($company->currentAccountID())->calculate()->save();
    if($client?->currentAccountID()) ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
    if (app()->getLocale() == 'en') {
      return redirect()->route('containers.index')->with('success', 'Container Deleted Successfully');
    } else {
      return redirect()->route('containers.index')->with('success', 'تم حذف الحاوية بنجاح');
    }
  }

  public function items(Request $request)
  {
    $this->validate($request, [
      'item_id' => 'required|exists:container_items,id',
      'container_id' => 'required|exists:containers,id',
      'item_dollar_price' => 'required|numeric',
      'dozen_quantity' => 'required|numeric',
      'notes' => 'sometimes|nullable|string',
    ]);
    $item = ContainerItem::findOrFail($request->item_id);
    $total = $item->total;
    if($request->item_dollar_price && $request->dozen_quantity){
      $total = $request->item_dollar_price * $request->dozen_quantity;
    }
    $item->update([
      'total' => $total,
      'notes' => $request->notes
    ]);

    $container = Container::with(['client'])->find($request->container_id);
    $client = $container?->client;

    if($client?->currentAccountID()) ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();

    if (app()->getLocale() == 'en') {
      return response()->json(['success' => 'Item Saved Successfully', 'data' => $item]);
    } else {
      return response()->json(['success' => 'تم حفظ البند بنجاح', 'data' => $item]);
    }
  }
  public function itemsAll(Request $request)
  {
    $this->validate($request, [
      'items' => 'array',
    ]);
    $container = null;
    foreach ($request->items as $i){
      $itemArray = json_decode($i, true);
      Validator::make($itemArray, [
        'container_id' => 'required|integer',
        'item_id' => 'required|integer|exists:container_items,id',
        'item_dollar_price' => 'required|numeric',
        'dozen_quantity' => 'required|numeric',
        'notes' => 'nullable|string',
      ]);
      $item = ContainerItem::findOrFail($itemArray['item_id']);
      $total = $item->total;
      if($itemArray['item_dollar_price'] && $itemArray['dozen_quantity']){
        $total = $itemArray['item_dollar_price'] * $itemArray['dozen_quantity'];
      }
      $item->update([
        'total' => $total,
        'notes' => $itemArray['notes'],
      ]);
    }
    $container = Container::with(['client'])->find($request->container_id);
    $client = $container?->client;
    if($client?->currentAccountID()) ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();

    if (app()->getLocale() == 'en') {
      return response()->json(['success' => 'Item Saved Successfully']);
    } else {
      return response()->json(['success' => 'تم حفظ البند بنجاح']);
    }
  }
}
