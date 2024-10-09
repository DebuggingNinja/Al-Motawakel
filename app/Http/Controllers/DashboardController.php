<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientClaimReminder;
use App\Models\CompanyPaymentReminder;
use App\Models\Expense;
use App\Models\Item;
use App\Models\Order;
use App\Models\Reminder;
use App\Models\Repository;
use App\Models\ShippingCompany;
use App\Models\Supplier;
use App\Models\SupplierPaymentReminder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

  /**
   * Display dashbnoard demo one of the resource.
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    $title = "Dashboard Home";
    $description = "Dashboard Home Page";
    $repos = Repository::with(['items' => function ($query) {
      $query->where('status', '=', 'received');
    }])->get();
    $requestedItems = Order::with(['client', 'items' => function($i){
      return $i->where('status', 'requested')->orderBy('check_date', 'asc')->limit(1);
    }])->whereHas('items', fn ($q) => $q->where('status', 'requested')->orderBy('check_date', 'asc'))->get();

    $waitingItems = Item::with(['order.client'])
      ->where('receive_date', '!=', null)
      //->where('receive_date', '>=', today()->toDateString())
      ->where('status', 'waiting')
      ->orderBy('receive_date', 'asc')
      ->get();

    $SupplierPaymentReminders = $this->get10SupplerReminders();
    $CompanyPaymentReminders = $this->get10CompanyReminders();
    $ClientClaimReminders = $this->get10ClientReminders();
    $clients_cbm = [];

    Client::with(['orders', 'orders.items' => function ($q) {
      $q->where('status', 'received');
    }, 'orders.repo', 'orders.items.repo'])->whereHas('orders', function ($q){
        $q->whereHas('items', fn ($i) => $i->where('status', 'received'));
      })->get()->map(function ($item) use (&$clients_cbm){
        $item->orders->map(function ($orders) use($item, &$clients_cbm){
          $orders->items->map(function ($o_item) use($item, &$clients_cbm){
            if(!isset($clients_cbm[$item->id . "-" . $o_item->repository_id])){
              $clients_cbm[$item->id . "-" . $o_item->repository_id] = (object) [
                'client_name' => $item->name,
                'client_code' => $item->code,
                'repo_name'   => $o_item?->repo?->name,
                'total_cbm'   => 0
              ];
            }
            $clients_cbm[$item->id . "-" . $o_item->repository_id]->total_cbm += $o_item->cbm;
          });
        });
    });

    $clients_cbm = array_filter($clients_cbm, fn ($ccbm) => $ccbm->total_cbm);

    $globalReminders = Reminder::orderBy('date')->get();
    return view('pages.dashboard.index', compact('title', 'description', 'repos',
      'requestedItems', 'waitingItems', 'globalReminders', 'ClientClaimReminders', 'SupplierPaymentReminders', 'clients_cbm',
      'CompanyPaymentReminders'));
  }

  public function deleteReminder($id)
  {
      $reminder = Reminder::findOrFail($id)->delete();
      return $reminder ? response()->json(['error' => false, 'message' => 'reminder deleted']) :
    response()->json(['error' => true, 'message' => 'failed to delete']);
  }
  public function addReminder(Request $request)
  {
    $request->validate([
      'description' => 'required',
      'date' => 'required|date|after:'.date("Y-m-d", strtotime("-2 day")),
    ]);
    Reminder::create([
      'description' => $request->description,
      'date' => $request->date,
    ]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Reminder Created Successfully');
    } else {
 //     toastr()->success('تم إنشاء التذكير بنجاح');
    }
    return redirect()->route('dashboard.index')->with('success', 'Reminder Created Successfully');
  }


  public function get10SupplerReminders()
  {
    $paymentReminders = Supplier::with([
      'activeAccount',
      'reminder' => fn($q) => $q->where('status', '<>', 'paid')->whereNotNull('payment_date')->latest()
    ])->whereHas('reminder', fn($q) => $q->where('status', '<>', 'paid')->whereNotNull('payment_date'))
      ->limit(10)->get();

    return $paymentReminders->sortBy(function ($item) {
      return $item->reminder->isEmpty() ? null : $item->reminder->first()->payment_date;
    })->values()->map(
      function ($item) {
        return [
          'id'              => $item->id,
          'name'            => $item->name,
          'code'            => $item->code,
          'balance_dollar'  => $item->activeAccount?->total_dollar,
          'balance_rmb'     => $item->activeAccount?->total_rmb,
          'payment_date'    => ($item->reminder[0] ?? null)?->payment_date,
          'status'          => ($item->reminder[0] ?? null)?->status,
        ];
      }
    );
  }

  public function get10CompanyReminders()
  {
    $paymentReminders = ShippingCompany::with([
      'activeAccount',
      'reminder' => fn($q) => $q->where('status', '<>', 'paid')->whereNotNull('payment_date')->latest()
    ])->whereHas('reminder', fn($q) => $q->where('status', '<>', 'paid')->whereNotNull('payment_date'))
      ->limit(10)->get();

    // Merge payment reminders and suppliers into a single collection
    return $paymentReminders->sortBy(function ($item) {
      return $item->reminder->isEmpty() ? null : $item->reminder->first()->payment_date;
    })->values()->map(
      function ($item) {
        return [
          'id'              => $item->id,
          'name'            => $item->name,
          'code'            => $item->code,
          'balance_dollar'  => $item->activeAccount?->total_dollar,
          'balance_rmb'     => $item->activeAccount?->total_rmb,
          'payment_date'    => ($item->reminder[0] ?? null)?->payment_date,
          'status'          => ($item->reminder[0] ?? null)?->status,
        ];
      }
    );
  }

  public function get10ClientReminders()
  {

    $paymentReminders = Client::with([
      'activeAccount',
      'reminder' => fn($q) => $q->where('status', '<>', 'paid')->whereNotNull('due_date')->latest()
    ])->whereHas('reminder', fn($q) => $q->where('status', '<>', 'paid')->whereNotNull('due_date'))
      ->limit(10)->get();

    // Merge payment reminders and suppliers into a single collection
    return $paymentReminders->sortBy(function ($item) {
      return $item->reminder->isEmpty() ? null : $item->reminder->first()->due_date;
    })->values()->map(
      function ($item) {
        return [
          'id'              => $item->id,
          'name'            => $item->name,
          'code'            => $item->code,
          'balance_dollar'  => $item->activeAccount?->total_dollar,
          'balance_rmb'     => $item->activeAccount?->total_rmb,
          'payment_date'    => ($item->reminder[0] ?? null)?->due_date,
          'status'          => ($item->reminder[0] ?? null)?->status,
        ];
      }
    );

  }
}
