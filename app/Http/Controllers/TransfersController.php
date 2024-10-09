<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Ledger;
use App\Models\Transfer;
use App\Services\Financials\BalanceCalculator;
use App\Services\Financials\ClientBalanceCalculator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransfersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view transfers')->only('index');
    $this->middleware('can:add transfers')->only('create', 'store');
    $this->middleware('can:update transfers')->only('update', 'edit');
    $this->middleware('can:delete transfers')->only('destroy');
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
    $transfers = Transfer::with('ledgers.client')->where([
      ['id', '!=', Null],
      [function ($query) use ($request) {
        if (($term = $request->search)) {
          $query->orWhere('from', 'LIKE', '%' . $term . '%')
            ->orWhere('date', 'LIKE', '%' . $term . '%')
            ->get();
        }
      }]
    ])->latest()->paginate($per_page);
    $title = "Transfers";
    $description = "Show Transfers";
    // remember to remove clients
    return view('transfers.index', compact('title', 'description', 'transfers'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create new Transfer";
    $description = "Create New Transfer Page";
    $clients = Client::all();
    return view('transfers.create', compact('title', 'description', 'clients'));
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
      'from' => 'required',
      'date' => 'date',
      'number' => 'nullable|sometimes',
      'transfer_usd' => 'required|numeric',
      'client_id' => 'required|exists:clients,id',
    ]);
    try {
      DB::beginTransaction();
      $transfer = Transfer::create([
        'from' => $request->from,
        'date' => $request->date,
        'number' => $request->number,
        'amount_usd' => $request->transfer_usd,
        'amount_rmb' => 0,
        'rate' => 0,
      ]);
      $client = Client::findOrFail($request->client_id);
      Ledger::create([
        'date' => now(),
        'description' => $request->from,
        'credit' => $request->transfer_usd,
        'currency' => 'usd',
        'action' => 'transfer',
        'client_id' => $client->id,
        'transfer_id' => $transfer->id,
        'account_id' => $client->currentAccountID()
      ]);
      if($client->currentAccountID())  ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
      DB::commit();

      return redirect()->route('transfers.index')->with('success', 'Transfer Created Successfully');

    } catch (Exception $e) {
      DB::rollBack();
      dd($e->getMessage());
      return redirect()->back()->with('error', 'Failed to Create Transfer');
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
    $transfer = Transfer::with('ledgers.client')->findOrFail($id);
    $title = "Transfer";
    $description = "Transfer Page";
    return view('transfers.show', compact('title', 'description', 'transfer'));
  }

  public function destroy($id)
  {
    $supplier = Transfer::findOrFail($id);
    $supplier->ledgers()->delete();
    $supplier->delete();
    if (app()->getLocale() == 'en') {
      return redirect()->back()->with('success', 'Transfer Deleted Successfully');
    } else {
      return redirect()->back()->with('success', 'تم حذف الحوالة');
    }
  }


}
