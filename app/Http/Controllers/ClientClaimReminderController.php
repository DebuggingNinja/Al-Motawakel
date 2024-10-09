<?php

namespace App\Http\Controllers;

use App\Enums\FinancialType;
use App\Models\Client;
use App\Models\ClientClaimReminder;
use Illuminate\Http\Request;

class ClientClaimReminderController extends Controller
{
  public function index(Request $request)
  {
    $per_page = (!empty(session('pagination_per_page'))) ? session('pagination_per_page') : 20;

    $paymentReminders = Client::with(['activeAccount', 'reminder' => fn($q) => $q->where('status', '<>', 'paid')->latest()])
      ->whereHas('reminder', fn($q) => $q->where('status', '<>', 'paid'));

    if ($request->search)
      $paymentReminders->where('name', 'LIKE', '%' . $request->search . '%')
        ->orWhere('phone', 'LIKE', '%' . $request->search . '%')
        ->orWhere('code', 'LIKE', '%' . $request->search . '%');

    $paymentReminders = $paymentReminders->forPage(request()->page ?? 1, $per_page)->get()->pluck('reminder')->flatten()
      ->sortBy(function ($item) {
      return $item->due_date;
    })->values();

    $paymentType = FinancialType::PAYMENT_FROM_CLIENT;
    $title = __('client.Clients\' Claim Reminders');
    $description = __('client.Clients\' Claim Reminders');
    return view('client_claim_reminder.index', compact('title', 'description', 'paymentReminders', 'paymentType'));
  }

  public function changeDate(Request $request)
  {

    $request->validate([
      'new_payment_date' => ['required', 'date']
    ]);
    ClientClaimReminder::findOrFail($request->id)->update(['due_date' => $request->new_payment_date]);
    if (app()->getLocale() == 'en') {
// //     toastr()->success('Date Changed Successfully');
    } else {
// //     toastr()->success('تم تغيير الموعد بنجاح');
    }
    return redirect()->back()->with('success', 'Date Changed Successfully');
  }

  public function scheduleDate(Request $request)
  {
    $request->validate([
      'new_payment_date' => ['required', 'date'],
      'client_id'      => ['required', 'exists:clients,id']
    ]);
    ClientClaimReminder::create([
      'due_date'  => $request->new_payment_date,
      'client_id'   => $request->client_id,
    ]);
    if (app()->getLocale() == 'en') {
      return redirect()->back()->with('success', 'Date Created Successfully');
    } else {
      return redirect()->back()->with('success', 'تم انشاء الموعد بنجاح');
    }
  }
  public function destroy($id)
  {
    ClientClaimReminder::findOrFail($id)->delete();
    if (app()->getLocale() == 'en') {
      return redirect()->back()->with('success', 'Deleted Successfully');
    } else {
      return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
  }

}
