<?php

namespace App\Http\Controllers;

use App\Enums\FinancialType;
use App\Models\Supplier;
use App\Models\SupplierPaymentReminder;
use Illuminate\Http\Request;

class SupplierPaymentReminderController extends Controller
{

  public function index(Request $request)
  {
    $per_page = (!empty(session('pagination_per_page'))) ? session('pagination_per_page') : 20;

    $paymentReminders = Supplier::with(['activeAccount', 'reminder' => fn($q) => $q->where('status', '<>', 'paid')->latest()])
      ->whereHas('reminder', fn($q) => $q->where('status', '<>', 'paid'));

    if ($request->search)
      $paymentReminders->where('name', 'LIKE', '%' . $request->search . '%')
        ->orWhere('phone', 'LIKE', '%' . $request->search . '%')
        ->orWhere('code', 'LIKE', '%' . $request->search . '%');

    $paymentReminders = $paymentReminders->forPage(request()->page ?? 1, $per_page)->get()->pluck('reminder')->flatten()
      ->sortBy(function ($item) {
      return $item->payment_date;
    })->values();

    $paymentType = FinancialType::PAYMENT_TO_SUPPLIER;
    $title = __('supplier.Suppliers\' Payments Reminders');
    $description = __('supplier.Suppliers\' Payments Reminders');
    return view('supplier_payment_reminder.index', compact('title', 'description', 'paymentReminders', 'paymentType'));
  }

  public function changeDate(Request $request)
  {

    $request->validate([
      'new_payment_date' => ['required', 'date']
    ]);
    SupplierPaymentReminder::findOrFail($request->id)->update(['payment_date' => $request->new_payment_date]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Date Changed Successfully');
    } else {
 //     toastr()->success('تم تغيير الموعد بنجاح');
    }
    return redirect()->back()->with('success', 'Date Changed Successfully');
  }

  public function scheduleDate(Request $request)
  {
    $request->validate([
      'new_payment_date' => ['required', 'date'],
      'supplier_id'      => ['required', 'exists:suppliers,id']
    ]);
    SupplierPaymentReminder::create([
      'payment_date'  => $request->new_payment_date,
      'supplier_id'   => $request->supplier_id,
    ]);
    if (app()->getLocale() == 'en') {
      return redirect()->back()->with('success', 'Date Created Successfully');
    } else {
      return redirect()->back()->with('success', 'تم انشاء الموعد بنجاح');
    }
  }
  public function destroy($id)
  {
    SupplierPaymentReminder::findOrFail($id)->delete();
    if (app()->getLocale() == 'en') {
      return redirect()->back()->with('success', 'Deleted Successfully');
    } else {
      return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
  }

}
