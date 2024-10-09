<?php

namespace App\Http\Controllers;

use App\Enums\FinancialType;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Ledger;
use App\Models\ShipperLedger;
use App\Models\ShippingCompany;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Services\Financials\BalanceCalculator;
use App\Services\Financials\ClientBalanceCalculator;
use App\Services\Financials\FinancialsService;
use App\Services\Financials\ShipperBalanceCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:view expenses')->only('index');
    $this->middleware('can:add expenses')->only('create', 'store');
    $this->middleware('can:update expenses')->only('update', 'edit');
    $this->middleware('can:delete expenses')->only('destroy');
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
    $expenses = Expense::with(['client', 'supplier'])->where([
      ['id', '!=', Null],
      [function ($query) use ($request) {
        if (($term = $request->search)) {
          $query->orWhere('description', 'LIKE', '%' . $term . '%')
            ->get();
        }
      }]
    ])->latest()->paginate($per_page);
    $title = "Expenses";
    $description = "Show Expenses";
    return view('expenses.index', compact('title', 'description', 'expenses'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $title = "Create new Expense";
    $description = "Create New Expense Page";
    $clients = Client::all(['id', 'code', 'name']);
    $suppliers = Supplier::all(['id', 'code', 'name']);
    $companies = ShippingCompany::all(['id', 'code', 'name']);
    $financialTypes = FinancialType::getConstants();
    return view('expenses.create', compact('title', 'description', 'clients', 'financialTypes', 'suppliers', 'companies'));
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
      'description'     => 'required',
      'type'            => ['required', 'in:' . implode(',', FinancialType::getConstants())],
      'client_id' => [
        'sometimes',
        'required_if:type,' . implode(',', [FinancialType::PAYMENT_FROM_CLIENT, FinancialType::PAID_FOR_CLIENT]),
        'exists:clients,id',
      ],
      'supplier_id' => [
        'sometimes',
        'required_if:type,' . implode(',', [FinancialType::PAYMENT_TO_SUPPLIER]),
        'exists:suppliers,id',
      ],
      'shipping_company_id' => [
        'sometimes',
        'required_if:type,' . implode(',', [FinancialType::PAYMENT_FOR_SHIPPING]),
        'exists:shipping_companies,id',
      ],
      'amount'          => ['required', 'numeric', 'min:0'],
      'rate'            => ['sometimes', 'nullable', 'numeric'],
      'currency'        => ['required', 'in:usd,rmb'],
      'date'            => ['required', 'date']
    ]);


    $financialService = new FinancialsService();
    if ($financialService->setRequest($request->toArray())->CreateFinacial()) {
      if (app()->getLocale() == 'en') {
        return redirect()->back()->with('success', 'Created Successfully');
      } else {
        return redirect()->back()->with('success', 'تم إنشاء الامر المالي بنجاح');
      }
    } else {
      if (app()->getLocale() == 'en') {
        return redirect()->back()->with('error', 'Failed to Create Financial');
      } else {
        return redirect()->back()->with('error', 'فشل في إنشاء الامر المالي');
      }
    }

    return redirect()->back();
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
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function incomeAndOutcome(Request $request)
  {
    $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::parse("- 1 month");
    $end = $request->end_time ? Carbon::parse($request->end_time) : now();

    $ledgers = [];

    $transfer = trans('expense.transfer');
    $income = $outcome = 0;

    Ledger::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->get()
      ->map(function ($item) use (&$ledgers,$transfer, &$income, &$outcome){

      $credit = $item['credit'];
      $debit = $item['debit'];

      if ($item['currency'] == 'usd'){
        $credit = $credit * $item['currency_rate'];
        $debit = $debit * $item['currency_rate'];
      }

      if($credit){
        $ledgers[Carbon::parse($item['date'])->format('Y-m-d')][] = [
          "reason" => $item['description'] . ($item['action'] == "transfer" ? " - " . "(" . $transfer . ")" : ""),
          "credit" => $credit,
          "currency" => $item['currency']
        ];
        $income += $credit;
      }
      if($debit){
        $ledgers[Carbon::parse($item['date'])->format('Y-m-d')][] = [
          "reason" => $item['description'],
          "debit" => $debit,
          "currency" => $item['currency']
        ];
        $outcome += $debit;
      }

    });

    SupplierLedger::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->get()
      ->map(function ($item) use (&$ledgers, &$income, &$outcome){

      $credit = $item['credit'];
      $debit = $item['debit'];

      if($credit){
        $ledgers[Carbon::parse($item['date'])->format('Y-m-d')][] = [
          "reason" => $item['description'],
          "credit" => $credit,
          "currency" => "rmb"
        ];
        $outcome += $credit;
      }
      if($debit){
        $ledgers[Carbon::parse($item['date'])->format('Y-m-d')][] = [
          "reason" => $item['description'],
          "debit" => $debit,
          "currency" => "rmb"
        ];
        $income += $debit;
      }

    });

    ShipperLedger::whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->get()
      ->map(function ($item) use (&$ledgers, &$income, &$outcome){

      $credit = $item['credit'];
      $debit = $item['debit'];

      if($credit){
        $ledgers[Carbon::parse($item['date'])->format('Y-m-d')][] = [
          "reason" => $item['description'],
          "debit" => $credit,
          "currency" => "rmb"
        ];
        $outcome += $credit;
      }
      if($debit){
        $ledgers[Carbon::parse($item['date'])->format('Y-m-d')][] = [
          "reason" => $item['description'],
          "credit" => $debit,
          "currency" => "rmb"
        ];
        $income += $debit;
      }

    });

    $title = "Income and Outcome";
    $description = "Income and Outcome details";
    ksort($ledgers);
    return view('expenses.statement', compact('title', 'description', 'ledgers', 'income', 'outcome'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $title         = 'Edit Expense';
    $description   = 'Edit Expense Page';
    $expense = Expense::whereNull('supplier_id')->whereNull('client_id')->findOrFail($id);
    $financialTypes = [FinancialType::RENT, FinancialType::SALARY, FinancialType::COMMISSIONS, FinancialType::OTHER];

    return view('expenses.edit', compact('title', 'description', 'expense', 'financialTypes'));
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
      'description' => 'required',
      'amount' => 'required|numeric',
      'rate'            => ['nullable', 'required_if:currency,usd', 'numeric'],
      'currency'        => ['required', 'in:usd,rmb'],
      'type'          => ['required', 'in:' . implode(',', [FinancialType::RENT, FinancialType::SALARY, FinancialType::COMMISSIONS, FinancialType::OTHER])],
      'date' => 'required|date'
    ]);
    $expense = Expense::whereNull('supplier_id')->whereNull('client_id')->findOrFail($id);
    if ($request->client_id == null) {
      $expense->update([
        'description' => $request->description,
        'amount'      => $request->amount,
        'rate'        => $request->currency == 'usd' ? $request->rate : null,
        'type'        => $request->type,
        'currency'    => $request->currency,
        'date'        => $request->date,
      ]);
    }
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Expense Updated Successfully');
    } else {
 //     toastr()->success('تم تحديث المصروف بنجاح');
    }
    return redirect()->route('expenses.index');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $expense = Expense::findOrFail($id);
    $expense->delete();
    if ($expense->supplier_id != null) {
      $supplier = Supplier::find($expense->supplier_id);

      SupplierLedger::create([
        'date' => now(),
        'description' => 'Deleting Expense - ' . $expense->description,
        'debit' => $expense->currency == 'rmb' ? $expense->amount : $expense->amount * $expense->rate,
        'balance' => 0,
        'action' => 'delete_expense',
        'supplier_id' => $supplier->id,
        'account_id' => $supplier->currentAccountID()
      ]);
      if($supplier->currentAccountID())  ShipperBalanceCalculator::init($supplier->currentAccountID())->calculate()->save();
    }

    if ($expense->client_id != null) {
      $client = Client::find($expense->client_id);
      if ($expense->type == FinancialType::PAYMENT_FROM_CLIENT) {

        // الغاء الدفع بيكون باني بدي القيمه الي دفعهالي ك كريديت
        Ledger::create([
          'date' => now(),
          'description' => 'Deleting ' . FinancialType::PAYMENT_FROM_CLIENT . ' - ' . $expense->description,
          'credit' => $expense->amount,
          'currency' => $expense->currency,
          'balance' => 0,
          'action' => 'delete',
          'client_id' => $client->id,
          'account_id' => $client->currentAccountID()
        ]);
        if($client->currentAccountID())  ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
      } else {
        // الغاء الدفع للعميل باني احطله الفلوس الي دفعتها ف الديبيت
        Ledger::create([
          'date' => now(),
          'description' => 'Deleting ' . FinancialType::PAID_FOR_CLIENT . ' Expense - ' . $expense->description,
          'debit' => $expense->amount,
          'currency' => $expense->currency,
          'balance' => 0,
          'action' => 'delete',
          'client_id' => $client->id,
          'account_id' => $client->currentAccountID()
        ]);
        if($client->currentAccountID())  ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
      }
    }
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Expense Deleted Successfully');
    } else {
 //     toastr()->success('تم حذف المصروف بنجاح');
    }
    return redirect()->route('expenses.index');
  }
}
