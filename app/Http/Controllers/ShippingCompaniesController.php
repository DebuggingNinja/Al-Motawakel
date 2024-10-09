<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Container;
use App\Models\ShippingCompany;
use App\Services\Financials\ShipperBalanceCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShippingCompaniesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view shipping companies')->only('index');
    $this->middleware('can:add shipping companies')->only('create', 'store');
    $this->middleware('can:update shipping companies')->only('update', 'edit');
    $this->middleware('can:delete shipping companies')->only('destroy');
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
    $companies = ShippingCompany::with('activeAccount')->where([
      ['name', '!=', Null],
      [function ($query) use ($request) {
        if (($term = $request->search)) {
          $query->orWhere('name', 'LIKE', '%' . $term . '%')
            ->orWhere('email', 'LIKE', '%' . $term . '%')
            ->orWhere('phone', 'LIKE', '%' . $term . '%')
            ->orWhere('phone2', 'LIKE', '%' . $term . '%')
            ->orWhere('code', 'LIKE', '%' . $term . '%')
            ->get();
        }
      }]
    ])->paginate($per_page);
    $title = "Shipping Companies";
    $description = "Show Shipping Companies";
    return view('shipping_companies.index', compact('title', 'description', 'companies'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create new Company";
    $description = "Create New Shipping Company Page";
    return view('shipping_companies.create', compact('title', 'description'));
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
      'code' => 'required|unique:shipping_companies,code',
      'email' => 'email|unique:shipping_companies|nullable',
      'phone' => 'nullable|unique:shipping_companies',
      'phone2' => 'nullable|unique:shipping_companies',
      'address' => 'nullable',
    ]);
    ShippingCompany::create([
      'name' => $request->name,
      'code' => $request->code,
      'email' => $request->email,
      'phone' => $request->phone,
      'phone2' => $request->phone2,
      'address' => $request->address,
    ]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Company Created Successfully');
    } else {
 //     toastr()->success('تم إنشاء الشركة بنجاح');
    }
    return redirect()->route('shipping_companies.index',)->with('success', 'Company Created Successfully');
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

  public function statement(Request $request, $id)
  {

    $company = ShippingCompany::with('activeAccount')->findOrFail($id);

    if(!$request->account){
      if(!$company->activeAccount){
        if(!($account = $company->currentAccount()))
          $account = Account::create([
            'model' => ShippingCompany::class,
            'model_id' => $company->id,
            'start_date' => date('Y-m-d'),
          ]);
        $company->update(['active_account_id' => $account->id]);
        $company->ledgers()->whereNull('account_id')->update(['account_id' => $account->id]);
        $company->containers()->whereNull('company_account_id')->update(['company_account_id' => $account->id]);
      }else $account = $company->activeAccount;
    }else $account = $company->getAccount($request->account);

    $calculatorService = ShipperBalanceCalculator::init($account->id)->calculate();
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

    $calculatorService->getAccount()->shipperLedgers->map(function ($item) use(&$ledgers, $transfer){
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

    $calculatorService->getAccount()->companyContainers->map(function ($item) use (&$ledgers){

      if($item->cost_dollar){
        $ledgers[$item->created_at->format('Y-m-d H:i:s')][] = [
          "reason" => $item->serial_number,
          "credit" => $item->cost_dollar,
          "currency" => "dollar"
        ];
      }

      if($item->cost_rmb){
        $ledgers[$item->created_at->format('Y-m-d H:i:s')][] = [
          "reason" => $item->serial_number,
          "credit" => $item->cost_rmb,
          "currency" => "rmb"
        ];
      };
      if($item->back_rmb){
        $ledgers[$item->created_at->format('Y-m-d H:i:s')][] = [
          "reason" => $item->serial_number . ' (Back RMB)',
          "debit" => $item->back_rmb,
          "currency" => "rmb"
        ];
      };
    });

    $title = "Shipping Company Statement";
    $description = "Shipping Company Statement Page";
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
    $accounts = $company->allAccounts();
    return view('shipping_companies.statement', compact('company', 'title', 'balance', 'balance_rmb', 'description', 'ledgers', 'accounts', 'account'));
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $title         = 'Edit Company';
    $description   = 'Edit Company Page';
    $company = ShippingCompany::findOrFail($id);
    return view('shipping_companies.edit', compact('title', 'description', 'company'));
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
      'code' => 'required|unique:shipping_companies,code,' . $id,
      'email' => 'nullable|email|unique:shipping_companies,email,' . $id,
      'phone' => 'nullable|unique:shipping_companies,phone,' . $id,
      'phone2' => 'nullable|unique:shipping_companies,phone2,' . $id,
      'address' => 'nullable',
    ]);
    $company = ShippingCompany::findOrFail($id);
    $company->update([
      'name' => $request->name,
      'code' => $request->code,
      'email' => $request->email,
      'phone' => $request->phone,
      'phone2' => $request->phone2,
      'address' => $request->address,
    ]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Company Updated Successfully');
    } else {
 //     toastr()->success('تم تحديث الشركة بنجاح');
    }
    return redirect()->route('shipping_companies.index')->with('success', 'Company Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $find_company = ShippingCompany::findOrFail($id);
    $find_company->delete();
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Company Deleted Successfully');
    } else {
 //     toastr()->success('تم حذف الشركة بنجاح');
    }
    return redirect()->route('shipping_companies.index')->with('success', 'Company Deleted Successfully');
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
    $client = ShippingCompany::findOrFail($id);
    $service = ShipperBalanceCalculator::init($client->active_account_id);

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
