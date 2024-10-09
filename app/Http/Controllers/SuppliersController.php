<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Item;
use App\Models\Order;
use App\Models\Supplier;
use App\Services\Financials\SupplierBalanceCalculator;
use App\Services\Suppliers\SuppliersService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view suppliers')->only('index');
    $this->middleware('can:add suppliers')->only('create', 'store');
    $this->middleware('can:update suppliers')->only('update', 'edit');
    $this->middleware('can:delete suppliers')->only('destroy');
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
    $suppliers = Supplier::with('activeAccount')->where([
      ['name', '!=', Null],
      [function ($query) use ($request) {
        if (($term = $request->search)) {
          $query->orWhere('name', 'LIKE', '%' . $term . '%')
            ->orWhere('phone', 'LIKE', '%' . $term . '%')
            ->orWhere('code', 'LIKE', '%' . $term . '%')
            ->get();
        }
      }]
    ])->paginate($per_page);
    $title = "Suppliers";
    $description = "Show Suppliers";
    return view('suppliers.index', compact('title', 'description', 'suppliers'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create new Supplier";
    $description = "Create New Supplier Page";
    return view('suppliers.create', compact('title', 'description'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function API_store(Request $request, SuppliersService $service)
  {
    $service->store($request);
    $suppliers = Supplier::all();
    if (app()->getLocale() == 'en')
      return response([
        'success'   => true,
        'message'   => 'Supplier Created Successfully',
        'suppliers' =>   $suppliers
      ]);
    return response([
      'success'   => true,
      'message'   => 'تم إنشاء المورد بنجاح',
      'suppliers' =>   $suppliers
    ]);
  }
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, SuppliersService $service)
  {
    $service->store($request);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Supplier Created Successfully');
    } else {
 //     toastr()->success('تم إنشاء المورد بنجاح');
    }
    return redirect()->route('suppliers.index')->with('success', 'Supplier Created Successfully');
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
    $title         = 'Edit Supplier';
    $description   = 'Edit Supplier Page';
    $supplier = Supplier::findOrFail($id);
    return view('suppliers.edit', compact('title', 'description', 'supplier'));
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
      'code' => 'required|unique:suppliers,code,' . $id,
      'phone' => 'nullable|unique:suppliers,phone,' . $id,
      'store_number' => 'nullable'
    ]);
    $supplier = Supplier::findOrFail($id);
    $supplier->update([
      'name' => $request->name,
      'code' => $request->code,
      'phone' => $request->phone,
      'store_number' => $request->store_number
    ]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Supplier Updated Successfully');
    } else {
 //     toastr()->success('تم تحديث المورد بنجاح');
    }
    return redirect()->route('suppliers.index')->with('success', 'Supplier Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $supplier = Supplier::findOrFail($id);
    $supplier->delete();
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Supplier Deleted Successfully');
    } else {
 //     toastr()->success('تم حذف المورد بنجاح');
    }
    return redirect()->route('suppliers.index')->with('success', 'Supplier Deleted Successfully');
  }


  public function statement(Request $request, $id)
  {

    $supplier = Supplier::with('activeAccount')->findOrFail($id);

    if(!$request->account){
      if(!$supplier->activeAccount){
        if(!($account = $supplier->currentAccount()))
          $account = Account::create([
            'model' => Supplier::class,
            'model_id' => $supplier->id,
            'start_date' => date('Y-m-d'),
          ]);
        $supplier->update(['active_account_id' => $account->id]);
        $supplier->ledgers()->whereNull('account_id')->update(['account_id' => $account->id]);
        $supplier->orders()->whereNull('supplier_account_id')->update(['supplier_account_id' => $account->id]);
      }else $account = $supplier->activeAccount;
    }else $account = $supplier->getAccount($request->account);

    $calculatorService = SupplierBalanceCalculator::init($account->id)->calculate();
    if(!$account->end_date) $calculatorService->save();

    $balance = $calculatorService->getDollarBalance();
    $balance_rmb = $calculatorService->getRMBBalance();
    $ledgers = [];
    $transfer = trans('expense.transfer');

    $fields = ['starting_balance_dollar' => 'dollar', 'starting_balance_rmb' => 'rmb'];

    foreach ($fields as $field => $crn){
      if($calculatorService->getAccount()->{$field} > 0){
        $ledgers[$calculatorService->getAccount()->created_at->format('Y-m-d H:i:s')][] = [
          "reason" => __('statement.start_balance') . " " . __('statement.'.$crn),
          "credit" =>  $calculatorService->getAccount()->{$field},
          "currency" => $crn
        ];
      }
      if($calculatorService->getAccount()->{$field} < 0){
        $ledgers[$calculatorService->getAccount()->created_at->format('Y-m-d H:i:s')][] = [
          "reason" => __('statement.start_balance') . " " . __('statement.'.$crn),
          "debit" =>  $calculatorService->getAccount()->{$field},
          "currency" => $crn
        ];
      }
    }

    $calculatorService->getAccount()->supplierLedgers->map(function ($item) use(&$ledgers, $transfer){
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

    $calculatorService->getAccount()->supplierOrders->map(function ($item) use (&$ledgers){
      $total = $item->items->flatten()->sum('total');

      $ledgers[$item->created_at->format('Y-m-d H:i:s')][] = [
        "reason" => $item->code,
        "credit" => $total,
        "currency" => "rmb"
      ];

    });

    $title = "Supplier Statement";
    $description = "Supplier Statement Page";
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
    $accounts = $supplier->allAccounts();
    return view('suppliers.statement', compact('supplier', 'title', 'balance', 'description', 'ledgers', 'accounts', 'account', 'balance', 'balance_rmb'));
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
    $client = Supplier::findOrFail($id);
    $service = SupplierBalanceCalculator::init($client->active_account_id);

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
