<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use App\Models\Container;
use App\Models\ContainerItem;
use App\Models\Item;
use App\Models\Setting;
use App\Services\Financials\BalanceCalculator;
use App\Services\Financials\ClientBalanceCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class ClientsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view clients')->only('index');
    $this->middleware('can:add clients')->only('create', 'store');
    $this->middleware('can:update clients')->only('update', 'edit');
    $this->middleware('can:delete clients')->only('destroy');
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
    $clients = Client::with('activeAccount')->where([
      ['name', '!=', Null],
      [function ($query) use ($request) {
        if (($term = $request->search)) {
          $query->orWhere('name', 'LIKE', '%' . $term . '%')
            ->orWhere('email', 'LIKE', '%' . $term . '%')
            ->orWhere('phone', 'LIKE', '%' . $term . '%')
            ->orWhere('phone2', 'LIKE', '%' . $term . '%')
            ->orWhere('mark', 'LIKE', '%' . $term . '%')
            ->orWhere('code', 'LIKE', '%' . $term . '%')
            ->get();
        }
      }]
    ])->paginate($per_page);
    $title = "Clients";
    $description = "Show Clients";
    return view('clients.index', compact('title', 'description', 'clients'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create new Client";
    $description = "Create New Client Page";
    return view('clients.create', compact('title', 'description'));
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
      'name' => 'required',
      'code' => 'required|unique:clients,code',
      'email' => 'email|unique:clients|nullable',
      'phone' => 'nullable|unique:clients',
      'phone2' => 'nullable',
      'address' => 'nullable',
      'mark' => 'required',
    ]);
    Client::create([
      'name' => $request->name,
      'code' => $request->code,
      'email' => $request->email,
      'phone' => $request->phone,
      'phone2' => $request->phone2,
      'address' => $request->address,
      'mark' => $request->mark,
    ]);
    if (app()->getLocale() == 'en') {
// //     toastr()->success('Client Created Successfully');
    } else {
// //     toastr()->success('تم إنشاء العميل بنجاح');
    }
    return redirect()->route('clients.index',)->with('success', 'Client Created Successfully');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $title         = 'Show Client';
    $description   = 'Show Client Page';
    $client = Client::with('ledgers')->findOrFail($id);
    $ledgers = $client->ledgers->all();
    return view('clients.show', compact('title', 'description', 'client', 'ledgers'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $title         = 'Edit Client';
    $description   = 'Edit Client Page';
    $client = Client::findOrFail($id);
    return view('clients.edit', compact('title', 'description', 'client'));
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
      'name' => 'required',
      'code' => 'required|unique:clients,code,' . $id,
      'email' => 'nullable|email|unique:clients,email,' . $id,
      'phone' => 'nullable|unique:clients,phone,' . $id,
      'phone2' => 'nullable',
      'address' => 'nullable',
      'mark' => 'required',
    ]);
    $client = Client::findOrFail($id);
    $client->update([
      'name' => $request->name,
      'code' => $request->code,
      'email' => $request->email,
      'phone' => $request->phone,
      'phone2' => $request->phone2,
      'address' => $request->address,
      'mark' => $request->mark,
    ]);
    if (app()->getLocale() == 'en') {
// //     toastr()->success('Client Updated Successfully');
    } else {
// //     toastr()->success('تم تحديث العميل بنجاح');
    }
    return redirect()->route('clients.index')->with('success', 'Client Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $find_client = Client::findOrFail($id);
    $find_client->delete();
    if (app()->getLocale() == 'en') {
      return redirect()->route('clients.index')->with('success', 'Client Deleted Successfully');
    } else {
      return redirect()->route('clients.index')->with('success', 'تم حذف العميل بنجاح');
    }
  }

  public function download_ledger($id)
  {
    $client = Client::with('ledgers')->findOrFail($id);
    $ledgers = $client->ledgers->all();
    $fileBannerPath = Setting::where('key', 'file_banner')->value('value');
    if (!$fileBannerPath) {
      $fileBannerPath = 'ledger_file.jpg';
    }
    $date = now()->format('d-m-Y');
    $pdf = Pdf::loadView('clients.ledger-file', compact('client', 'ledgers', 'date', 'fileBannerPath'));
    return $pdf->download($client->name . '-' . $date . '.pdf');
  }

  public function statement(Request $request, $id)
  {

    $client = Client::with('activeAccount')->findOrFail($id);

    if(!$request->account){
      if(!$client->activeAccount){
        if(!($account = $client->currentAccount()))
          $account = Account::create([
            'model' => Client::class,
            'model_id' => $client->id,
            'start_date' => date('Y-m-d'),
          ]);
        $client->update(['active_account_id' => $account->id]);
        $client->ledgers()->whereNull('account_id')->update(['account_id' => $account->id]);
        $client->orders()->whereNull('client_account_id')->update(['client_account_id' => $account->id]);
        $client->containers()->whereNull('client_account_id')->update(['client_account_id' => $account->id]);
      }else $account = $client->activeAccount;
    }else $account = $client->getAccount($request->account);

    $calculatorService = ClientBalanceCalculator::init($account->id)->calculate();
    if(!$account->end_date) $calculatorService->save();

    $balance = $calculatorService->getDollarBalance();
    $balance_rmb = $calculatorService->getRMBBalance();
    $ledgers = [];
    $transfer = trans('expense.transfer');

    if($calculatorService->getAccount()->starting_balance_dollar > 0){
      $ledgers[$calculatorService->getAccount()->created_at->format('Y-m-d H:i:s')][] = [
        "reason" => __('statement.start_balance') . " " . __('statement.dollar'),
        "credit" =>  $calculatorService->getAccount()->starting_balance_dollar,
        "currency" => 'dollar'
      ];
    }

    $calculatorService->getAccount()->ledgers->map(function ($item) use(&$ledgers, $transfer){
      $credit = $item['credit'];
      $debit = $item['debit'];

      if($credit){
        $ledgers[Carbon::parse($item['date'])->format('Y-m-d H:i:s')][] = [
          "reason" => $item['description'] . ($item['action'] == "transfer" ? " - " . "(" . $transfer . ")" : ""),
          "credit" =>  $item['credit'],
          "currency" => $item['currency']
        ];
      }

      if($debit){
        $ledgers[Carbon::parse($item['date'])->format('Y-m-d H:i:s')][] = [
          "reason" => $item['description'],
          "debit" =>  $item['debit'],
          "currency" => $item['currency']
        ];
      }

      return $item;

    });

    $calculatorService->getAccount()->clientContainers->map(function ($item) use (&$ledgers){
        $total = $item->items->flatten()->sum('total');

        if($item->cost_dollar)
          $ledgers[$item->created_at->format('Y-m-d H:i:s')][] = [
            "reason" => $item->serial_number . " " . trans('statement.shipping_cost'),
            "debit" => $item->cost_dollar,
            "currency" => "dollar"
          ];

        $ledgers[$item->created_at->format('Y-m-d H:i:s')][] = [
          "reason" => $item->serial_number . " " . trans('statement.shipping_items_cost'),
          "debit" => $total,
          "currency" => "dollar"
        ];

        if($item->cost_rmb){
          $ledgers[$item->created_at->format('Y-m-d H:i:s')][] = [
            "reason" => $item->serial_number  . " " . trans('statement.shipping_cost') . " RMB",
            "debit" => (float) number_format($item->cost_rmb / ($item->dollar_price ?? 1), 3, '.', ''),
            "currency" => "dollar"
          ];
        };
      });

    $title = "Client Statement";
    $description = "Client Statement Page";
    $account = $calculatorService->getAccount();
    ksort($ledgers);
    $total = $total_rmb = 0;
    foreach ($ledgers as $key => &$ledger){
      foreach ($ledger as &$l){
        if($l['currency'] == "rmb"){
          $total_rmb -= $l['debit'] ?? 0;
          $total_rmb += $l['credit'] ?? 0;
          $l["due_rmb"] = $total_rmb;
        }else{
          $total -= $l['debit'] ?? 0;
          $total += $l['credit'] ?? 0;
          $l["due"] = $total;
        }
      }
    }
    $accounts = $client->allAccounts();
    return view('clients.statement', compact('client', 'title', 'description', 'ledgers', 'balance', 'balance_rmb', 'account', 'accounts'));
  }

  public function updateDollarRate(Request $request)
  {
    $request->validate([
      'dollar_rate' => 'min:0',
      'account' => 'required|exists:accounts,id',
    ]);

    $account = Account::find($request->account);

    return $account->update([
      'dollar_rate' => $request->dollar_rate
    ]) ? redirect()->back()
            ->with('success', app()->getLocale() == 'en' ? 'Currency rate updated' : 'تم تغير سعر الصرف') :
          redirect()->back()
            ->with('error', app()->getLocale() == 'en' ? 'failed to updated' : 'فشل التعديل');

  }
  public function restartAccount(Request $request, $id)
  {
    $client = Client::findOrFail($id);
    $service = ClientBalanceCalculator::init($client->active_account_id);

    if(!$service->restartNewAccount()){
      return redirect()->back()->with('error',
        app()->getLocale() == 'en' ?
          'Failed to account closed and new one started' :
          'فشل إغلاق الحساب القديم وبدء اخر جديد'
      );
    }

    return $client->update(['active_account_id' => $service->getAccount()->id]) ? redirect()->back()->with('success',
      app()->getLocale() == 'en' ?
        'Old account closed and new one started' :
        'تم إغلاق الحساب القديم وبدء اخر جديد'
    ) : redirect()->back()->with('error',
      app()->getLocale() == 'en' ?
        'Failed to account closed and new one started' :
        'فشل إغلاق الحساب القديم وبدء اخر جديد'
    );

  }
}
