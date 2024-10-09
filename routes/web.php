<?php

use App\Http\Controllers\CompanyPaymentReminderController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrokersController;
use App\Http\Controllers\BuyShipItemsController;
use App\Http\Controllers\BuyShipOrdersController;
use App\Http\Controllers\ClientClaimReminderController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ContainersController;
use App\Http\Controllers\CurrentReopositoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\LedgersController;
use App\Http\Controllers\PaginationController;
use App\Http\Controllers\RepositoriesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShipItemsController;
use App\Http\Controllers\ShipOrdersController;
use App\Http\Controllers\ShippingCompaniesController;
use App\Http\Controllers\SupplierPaymentReminderController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\TransfersController;
use App\Http\Controllers\UsersController;
use App\Models\ClientClaimReminder;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('globalVariables')->group(function () {
  Route::get('/language/{language}', function ($language) {
    app()->setLocale($language);
    session()->put('language', $language);

    return redirect()->back();
  })->name('set_language');

  Route::get('uploads/{prefix}/{file}', function ($prefix, $file){
    if(!file_exists(storage_path() . "/app/public/" . $prefix . "/" . $file)) abort(404);
    return response()->file(storage_path() . "/app/public/" . $prefix . "/" . $file);
  });

  Route::get('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
  Route::post('/authenticate', [AuthController::class, 'authenticate'])->middleware('guest')->name('authenticate');

  Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.index');
  Route::delete('/delete-reminder/{id?}', [DashboardController::class, 'deleteReminder'])->name('dashboard.delete_reminder');
  Route::post('/', [DashboardController::class, 'addReminder'])->name('reminders.store');
  Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
  Route::resource('/users', UsersController::class);
  Route::get('/clients/{id}/download_ledger', [ClientsController::class, 'download_ledger'])->name('clients.download_ledger');
  Route::get('/clients/{id}/statement', [ClientsController::class, 'statement'])->name('clients.statement');
  Route::post('/clients/statement/update_dollar_rate', [ClientsController::class, 'updateDollarRate'])->name('clients.statement.update_dollar_rate');
  Route::get('/clients/{id}/restart_account', [ClientsController::class, 'restartAccount'])->name('clients.statement.restart_account');
  Route::resource('/clients', ClientsController::class);
  Route::resource('/shipping_companies', ShippingCompaniesController::class);
  Route::get('/repositories/current_items/{repo_id?}', [CurrentReopositoriesController::class, 'currentItems'])->name('repository.index');
  Route::post('/repositories/current_items', [CurrentReopositoriesController::class, 'currentItems'])->name('repository.current_items');
  Route::post('/repositories/confirm-sale', [CurrentReopositoriesController::class, 'confirmSale'])->name('repository.confirmSale');
  Route::post('/repositories/decline-sale', [CurrentReopositoriesController::class, 'declineSale'])->name('repository.declineSale');
  Route::post('/repositories/ship', [CurrentReopositoriesController::class, 'ship'])->name('repository.ship');
  Route::put('/repositories/update-quantity', [CurrentReopositoriesController::class, 'updateQuantity'])->name('repository.update_quantity');
  Route::put('/repositories/update-note', [CurrentReopositoriesController::class, 'updateNotes'])->name('repository.update_notes');
  Route::resource('/repositories', RepositoriesController::class);

  Route::resource('/products', ProductsController::class);
  Route::resource('/brokers', BrokersController::class);
  Route::resource('/buy_ship_orders', BuyShipOrdersController::class);
  Route::post('/order/set_commission', [BuyShipOrdersController::class, 'setCommission'])->name('order.set_commission');
  Route::resource('/buy_ship_items', BuyShipItemsController::class)->only(['store']);
  Route::post('/buy_ship_items/update', [BuyShipItemsController::class, 'update'])->name('buy_ship_items.update');
  Route::post('/buy_ship_items/update_bulk', [BuyShipItemsController::class, 'update_bulk'])->name('buy_ship_items.update_bulk');
  Route::post('/buy_ship_items/delete', [BuyShipItemsController::class, 'destroy'])->name('buy_ship_items.delete_items');
  Route::post('/buy_ship_items/copy', [BuyShipItemsController::class, 'copy'])->name('buy_ship_items.copy');
  Route::resource('/ship_orders', ShipOrdersController::class);
  Route::resource('/ship_items', ShipItemsController::class);
  Route::post('/containers/items', [ContainersController::class, 'items'])->name('containers.items');
  Route::post('/containers/items-all', [ContainersController::class, 'itemsAll'])->name('containers.items_all');
  Route::resource('/containers', ContainersController::class);
  Route::get('/pagination-per-page/{per_page}', [PaginationController::class, 'set_pagination_per_page'])->name('pagination_per_page');
  Route::post('/ledgers/credit', [LedgersController::class, 'credit'])->middleware(['auth', 'can:add ledgers'])->name('ledgers.credit');
  Route::post('/ledgers/debit', [LedgersController::class, 'debit'])->middleware(['auth', 'can:add ledgers'])->name('ledgers.debit');
  Route::resource('/ledgers', LedgersController::class)->only(['destroy']);
  Route::resource('/transfers', TransfersController::class);


  Route::get('/settings/edit', [SettingsController::class, 'edit'])->name('settings.edit');
  Route::get('/settings/colors', [SettingsController::class, 'colorSettings'])->name('settings.colors');
  Route::post('/settings/colors/update', [SettingsController::class, 'updateColors'])->name('settings.colors.update');
  Route::post('/settings/logo', [SettingsController::class, 'updateLogo'])->name('settings.updateLogo');
  Route::get('/settings/logo/show', [SettingsController::class, 'showLogo'])->name('settings.showLogo');

  Route::post('/settings/print_banner', [SettingsController::class, 'updatePrintBanner'])->name('settings.updatePrintBanner');
  Route::post('/settings/file_banner', [SettingsController::class, 'updateFileBanner'])->name('settings.updateFileBanner');
  Route::get('/settings/file_banner/show', [SettingsController::class, 'showFileBanner'])->name('settings.showFileBanner');

  Route::post('/settings/year', [SettingsController::class, 'updateYear'])->name('settings.updateYear');


  Route::resource('/expenses', ExpensesController::class);
  Route::get('/income-outcome', [ExpensesController::class, 'incomeAndOutcome'])->name('income.outcome');

  Route::resource('/suppliers', SuppliersController::class);
  Route::get('/suppliers/{id}/statement', [SuppliersController::class, 'statement'])->name('suppliers.statement');
  Route::post('/suppliers/statement/update_dollar_rate', [SuppliersController::class, 'updateDollarRate'])->name('suppliers.statement.update_dollar_rate');
  Route::get('/suppliers/{id}/restart_account', [SuppliersController::class, 'restartAccount'])->name('suppliers.statement.restart_account');

  Route::get('/shipping_companies/{id}/statement', [ShippingCompaniesController::class, 'statement'])->name('shipping_companies.statement');
  Route::post('/shipping_companies/statement/update_dollar_rate', [ShippingCompaniesController::class, 'updateDollarRate'])->name('shipping_companies.statement.update_dollar_rate');
  Route::get('/shipping_companies/{id}/restart_account', [ShippingCompaniesController::class, 'restartAccount'])->name('shipping_companies.statement.restart_account');


  Route::post('/api/suppliers/store', [SuppliersController::class, 'API_store'])->name('api.suppliers.store');



  // التذكيرات

  Route::get('/suppliers/payments/reminder', [SupplierPaymentReminderController::class, 'index'])->name('suppliers.payments.reminder');
  Route::delete('/suppliers/payments/reminder/{id}/remove', [SupplierPaymentReminderController::class, 'destroy'])->name('suppliers.payments.reminder.destroy');
  Route::post('/suppliers/payments/reminder/change', [SupplierPaymentReminderController::class, 'changeDate'])->name('suppliers.payments.reminder.change.date');
  Route::post('/suppliers/payments/reminder/create', [SupplierPaymentReminderController::class, 'scheduleDate'])->name('suppliers.payments.reminder.create.date');


  Route::get('/companies/payments/reminder', [CompanyPaymentReminderController::class, 'index'])->name('companies.payments.reminder');
  Route::delete('/companies/payments/reminder/{id}/remove', [CompanyPaymentReminderController::class, 'destroy'])->name('companies.payments.reminder.destroy');
  Route::post('/companies/payments/reminder/change', [CompanyPaymentReminderController::class, 'changeDate'])->name('companies.payments.reminder.change.date');
  Route::post('/companies/payments/reminder/create', [CompanyPaymentReminderController::class, 'scheduleDate'])->name('companies.payments.reminder.create.date');


  Route::get('/clients/claim/reminder', [ClientClaimReminderController::class, 'index'])->name('clients.claims.reminder');
  Route::delete('/clients/claim/reminder/{id}/remove', [ClientClaimReminderController::class, 'destroy'])->name('clients.claims.reminder.destroy');
  Route::post('/clients/claim/reminder/change', [ClientClaimReminderController::class, 'changeDate'])->name('clients.claims.reminder.change.date');
  Route::post('/clients/claim/reminder/create', [ClientClaimReminderController::class, 'scheduleDate'])->name('clients.claims.reminder.create.date');
});
