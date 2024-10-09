<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\KeyGenerator;
use App\Models\Order;
use App\Models\Repository;
use App\Models\ShipOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ShipOrdersController extends Controller
{


  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view ship orders')->only('index');
    $this->middleware('can:add ship orders')->only('create', 'store');
    $this->middleware('can:update ship orders')->only('update', 'edit');
    $this->middleware('can:delete ship orders')->only('destroy');
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
    $orders = Order::where('type', 'ship')
      ->whereHas('client', function (Builder $query) use ($request) {
        if (($term = $request->search)) {
          $query->where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('code', 'LIKE', '%' . $term . '%');
        }
        $query->where('type', 'ship');
      })->orWhereHas('repo', function (Builder $query) use ($request) {
        if (($term = $request->search)) {
          $query->where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('code', 'LIKE', '%' . $term . '%');
        }
        $query->where('type', 'ship');
      })->paginate($per_page);
    $title = "Ship Orders";
    $description = "Show Orders";
    return view('ship_orders.index', compact('title', 'description', 'orders'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create Ship Order";
    $description = "Create New Ship Order Page";
    $clients = Client::all(['id', 'name', 'code']);
    $repos = Repository::all(['id', 'name', 'code']);
    $countRows = Order::count();
    $file = $countRows > 0 ? ceil($countRows / 30) : 1;
    if ($countRows % 30 === 0) {
      $file++;
    }
    return view('ship_orders.create', compact('title', 'description', 'repos', 'clients', 'file'));
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
      'client' => 'required',
//      'registery' => 'required|numeric',
//      'paper' => 'required|numeric',
    ]);
    $order = Order::create([
//      'code'  => KeyGenerator::Code('ship_order'),
      'repository_id' => $request->repo,
      'client_id' => $request->client,
//      'registery' => $request->registery,
//      'paper' => $request->paper,
      'type' => 'ship'
    ]);

    $order->generateShipSerial();
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Order Created Successfully');
    } else {
 //     toastr()->success('تم إنشاء الطلب بنجاح');
    }
    return redirect()->route('ship_orders.edit', $order->id)->with('success', 'Ship Order Created Successfully');
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
    $title         = 'Edit Ship Order';
    $description   = 'Edit Ship Order Page';
    $order = Order::findOrFail($id);
    $clients = Client::all();
    $repos = Repository::all();
    return view('ship_orders.edit', compact('title', 'description', 'order', 'repos', 'clients'));
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
      'client' => 'required',
//      'registery' => 'required',
//      'paper' => 'required',
    ]);
    $order = Order::findOrFail($id);
    $order->update([
      'repository_id' => $request->repo,
      'client_id' => $request->client,
//      'registery' => $request->registery,
//      'paper' => $request->paper,
    ]);
    if(!$order->code) $order->generateShipSerial();
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Order Updated Successfully');
    } else {
 //     toastr()->success('تم تحديث الطلب بنجاح');
    }
    return redirect()->route('ship_orders.edit', $order->id)->with('success', 'Ship Order Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $find_order = Order::findOrFail($id);
    $find_order->delete();
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Order Deleted Successfully');
    } else {
 //     toastr()->success('تم حذف الطلب بنجاح');
    }
    return redirect()->route('ship_orders.index')->with('success', 'Ship Order Deleted Successfully');
  }
}
