<?php

namespace App\Services\Financials;

use App\Enums\FinancialType;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Ledger;
use App\Models\ShipperLedger;
use App\Models\ShippingCompany;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use Illuminate\Http\Request;

class FinancialsService
{
  protected $request;
  public function setRequest($request)
  {
    $this->request = $request;
    return $this;
  }
  public function CreateFinacial(): bool
  {

    return match ($this->request['type'] ?? null) {
      FinancialType::PAID_FOR_CLIENT      => $this->paymentForClient(),
      FinancialType::PAYMENT_FROM_CLIENT  => $this->paymentFromClient(),
      FinancialType::PAYMENT_TO_SUPPLIER  => $this->paymentToSupplier(),
      FinancialType::PAYMENT_FOR_SHIPPING => $this->paymentToShipping(),
      default => (bool) $this->createExpense(),
    };
  }

  public function paymentToSupplier()
  {
    if ($this->request) {
      $this->createExpense();
      $this->addPaymentToSupplier();
      return true;
    }
    return false;
  }
  public function paymentToShipping()
  {
    if ($this->request) {
      $this->createExpense();
      $this->addPaymentToShipping();
      return true;
    }
    return false;
  }
  public function paymentFromClient()
  {
    if ($this->request) {
      $this->createExpense();
      $this->addClientPayment();
      return true;
    }
    return false;
  }
  public function paymentForClient()
  {
    if ($this->request) {
      $this->createExpense();
      $this->addToCilentLedger();
      return true;
    }
    return false;
  }


  public function addPaymentToSupplier()
  {
    $supplier = Supplier::find($this->request['supplier_id']);
    $amount = $this->request['amount'];
    if($this->request['currency'] == "usd") $amount = $amount * ($this->request['rate'] ?? 1);
    SupplierLedger::create([
      'date'            => now(),
      'currency'        => 'rmb',
      'description'     => $this->request['description'],
      'debit'           => $amount, // الموضوع هنا عكس المديون هو انا فلما السبلاير يدفعلي كريديت انا المفروض بسددله فالديبيت
      'balance'         => 0,
      'action'          => 'debit',
      'supplier_id'     => $this->request['supplier_id'],
      'account_id'      => $supplier->currentAccountID()
    ]);
    if($supplier->currentAccountID()) SupplierBalanceCalculator::init($supplier->currentAccountID())->calculate()->save();
  }
  public function addPaymentToShipping()
  {
    $company = ShippingCompany::find($this->request['shipping_company_id']);
    ShipperLedger::create([
      'date'                => now(),
      'currency'            => $this->request['currency'],
      'description'         => $this->request['description'],
      'debit'               => $this->request['amount'], // الموضوع هنا عكس المديون هو انا فلما السبلاير يدفعلي كريديت انا المفروض بسددله فالديبيت
      'balance'             => 0,
      'action'              => 'debit',
      'shipping_company_id' => $this->request['shipping_company_id'],
      'account_id'          => $company->currentAccountID()
    ]);
    if($company->currentAccountID()) ShipperBalanceCalculator::init($company->currentAccountID())->calculate()->save();
  }
  public function addClientPayment()
  {
    $client = Client::findOrFail($this->request['client_id']);
    $amount = $this->request['amount'];
    if($this->request['currency'] == "rmb")
      $amount = $amount / ($this->request['rate'] ?? 1);
    Ledger::create([
      'date'            => now(),
      'description'     => $this->request['description'],
      'credit'          => $amount,
      'balance'         => 0,
      'action'          => 'credit',
      'currency'        => 'usd',
      'currency_rate'   => $this->request['rate'] ?? null,
      'client_id'       => $this->request['client_id'],
      'account_id'      => $client->currentAccountID()
    ]);
    if($client->currentAccountID()) ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
  }
  public function addToCilentLedger()
  {
    $client = Client::findOrFail($this->request['client_id']);
    $amount = $this->request['amount'];
    if($this->request['currency'] == "rmb")
      $amount = $amount / ($this->request['rate'] ?? 1);
    Ledger::create([
      'date'            => now(),
      'description'     => $this->request['description'],
      'debit'           => $amount,
      'balance'         => 0,
      'action'          => 'debit',
      'currency'        => 'usd',
      'currency_rate'   => $this->request['rate'] ?? null,
      'client_id'       => $this->request['client_id'],
      'account_id'      => $client->currentAccountID()
    ]);
    if($client->currentAccountID()) ClientBalanceCalculator::init($client->currentAccountID())->calculate()->save();
  }

  public function createExpense()
  {
    return Expense::create(
      [
        'description'           => $this->request['description'] ?? null,
        'amount'                => $this->request['amount'] ?? 0,
        'rate'                  => $this->request['rate'] ?? null,
        'currency'              => $this->request['currency'] ?? null,
        'date'                  => $this->request['date'] ?? null,
        'client_id'             => $this->request['client_id'] ?? null,
        'type'                  => $this->request['type'] ?? null,
        'supplier_id'           => $this->request['supplier_id'] ?? null,
        'shipping_company_id'   => $this->request['shipping_company_id'] ?? null,
      ]
    );
  }
}
