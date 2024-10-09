<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Ledger;
use App\Services\Financials\ClientBalanceCalculator;
use Illuminate\Http\Request;

class LedgersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('can:add ledgers')->only('credit', 'debit');
    $this->middleware('can:delete ledgers')->only('destroy');
  }
  public function credit(Request $request)
  {
    $client = Client::findOrFail($request->client_id);

    $request->validate([
      'credit' => 'required|numeric',
      'description' => 'required',
      'client_id' => 'required|exists:clients,id',
    ]);
    $ledger = Ledger::create([
      'date' => now(),
      'description' => $request->description,
      'credit' => $request->credit,
      'currency' => 'dollar',
      'action' => 'credit',
      'balance' => 0,
      'client_id' => $client->id,
      'account_id' => $client->currentAccountID()
    ]);
    if($client->currentAccountID())  ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
    if (app()->getLocale() == 'en') {
      return response()->json(['success' => 'Credit Successfully', 'data' => $ledger]);
    } else {
      return response()->json(['success' => 'تم الإضافة بنجاح', 'data' => $ledger]);
    }
  }
  public function debit(Request $request)
  {
    $client = Client::findOrFail($request->client_id);
    $previousBalance = $client->ledgers()->latest('date')->first()->balance;
    $request->validate([
      'debit' => 'required|numeric',
      'description' => 'required',
      'client_id' => 'required|exists:clients,id',
    ]);
    $ledger = Ledger::create([
      'date' => now(),
      'description' => $request->description,
      'debit' => $request->debit,
      'action' => 'debit',
      'currency' => 'dollar',
      'balance' => 0,
      'client_id' => $client->id,
      'account_id' => $client->currentAccountID()
    ]);
    if($client->currentAccountID())  ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
    if (app()->getLocale() == 'en') {
      return response()->json(['success' => 'Debit Successfully', 'data' => $ledger]);
    } else {
      return response()->json(['success' => 'تم الإضافة بنجاح', 'data' => $ledger]);
    }
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $ledger = Ledger::with('client')->findOrFail($id);
    $client = $ledger->client;
    $ledger->delete();
    if($client->currentAccountID())  ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
    if (app()->getLocale() == 'en') {
      return response()->json(['success' => 'Deleted Successfully', 'data' => $ledger]);
    } else {
      return response()->json(['success' => 'تم الحذف بنجاح', 'data' => $ledger]);
    }
  }
}
