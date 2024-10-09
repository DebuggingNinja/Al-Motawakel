<?php

namespace App\Http\Controllers;

use App\Enums\FinancialType;
use App\Models\ShippingCompany;
use App\Models\Supplier;
use App\Models\CompanyPaymentReminder;
use Illuminate\Http\Request;

class CompanyPaymentReminderController extends Controller
{

  public function index(Request $request)
  {
    $per_page = (!empty(session('pagination_per_page'))) ? session('pagination_per_page') : 20;

    $paymentReminders = ShippingCompany::with(['activeAccount', 'reminder' => fn($q) => $q->where('status', '<>', 'paid')->latest()])
      ->whereHas('reminder', fn($q) => $q->where('status', '<>', 'paid'));

    if ($request->search)
      $paymentReminders->where('name', 'LIKE', '%' . $request->search . '%')
        ->orWhere('phone', 'LIKE', '%' . $request->search . '%')
        ->orWhere('code', 'LIKE', '%' . $request->search . '%');

    $paymentReminders = $paymentReminders->forPage(request()->page ?? 1, $per_page)->get()->pluck('reminder')->flatten()
      ->sortBy(function ($item) {
      return $item->payment_date;
    })->values();
    $paymentType = FinancialType::PAYMENT_FOR_SHIPPING;
    $title = __('company.companies\' Payments Reminders');
    $description = __('company.companies\' Payments Reminders');
    return view('company_payment_reminder.index', compact('title', 'description', 'paymentReminders', 'paymentType'));
  }

  public function changeDate(Request $request)
  {

    $request->validate([
      'new_payment_date' => ['required', 'date']
    ]);
    CompanyPaymentReminder::findOrFail($request->id)->update(['payment_date' => $request->new_payment_date]);
    if (app()->getLocale() == 'en') {
 //     toastr()->success('Date Changed Successfully');
    } else {
 //     toastr()->success('تم تغيير الموعد بنجاح');
    }
    return redirect()->back();
  }

  public function scheduleDate(Request $request)
  {
    $request->validate([
      'new_payment_date' => ['required', 'date'],
      'shipping_company_id'      => ['required', 'exists:shipping_companies,id']
    ]);
    CompanyPaymentReminder::create([
      'payment_date'  => $request->new_payment_date,
      'shipping_company_id'   => $request->shipping_company_id,
    ]);
    if (app()->getLocale() == 'en') {
      return redirect()->back()->with('success', 'Date Created Successfully');
    } else {
      return redirect()->back()->with('success', 'تم انشاء الموعد بنجاح');
    }
  }
  public function destroy($id)
  {
    CompanyPaymentReminder::findOrFail($id)->delete();
    if (app()->getLocale() == 'en') {
      return redirect()->back()->with('success', 'Deleted Successfully');
    } else {
      return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
  }

}
