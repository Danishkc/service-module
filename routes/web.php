<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\InvoiceController;

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


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('home');
    Route::resource('services', ServiceController::class);
    Route::get('services/start-repair/{id}', [ServiceController::class, 'startRepair'])->name('services.startRepair');
    
    // Route::match(['put', 'get'], 'services/end-repair/{id}', [ServiceController::class, 'endRepair'])->name('services.endRepair');
    Route::put('services/end-repair/{id}', [ServiceController::class, 'endRepair'])->name('services.endRepair');
    Route::get('services/end-repair/{id}', [ServiceController::class, 'getEndRepair'])->name('services.getEndRepair');

    Route::put('services/repaired/{id}', [ServiceController::class, 'repaired'])->name('services.repaired');
    Route::get('services/repaired/{id}', [ServiceController::class, 'getRepaired'])->name('services.getRepaired');
    Route::delete('/services/delete-selected/{ids}', [ServiceController::class, 'destroySelected'])->name('services.destroySelected');
    // Route::match(['put', 'get'], 'services/repaired/{id}', [ServiceController::class, 'repaired'])->name('services.repaired');
    Route::post('services/customer', [ServiceController::class, 'storeCustomer'])->name('services.storeCustomer');
    Route::post('services/product', [ServiceController::class, 'storeProduct'])->name('services.storeProduct');
    Route::post('services/responsible', [ServiceController::class, 'storeResponsible'])->name('services.storeResponsible');
    Route::get('/services/cancelled-confirm-repair/{id}', [ServiceController::class, 'cancelConfirmRepair'])->name('services.cancelConfirmRepair');
    Route::get('/services/cancelled-under-repair/{id}', [ServiceController::class, 'cancelledUnderRepair'])->name('services.cancelledUnderRepair');

    Route::resource('quotation', QuotationController::class);
    Route::get('quotation/create/{id}', [QuotationController::class, 'create'])->name('quotation.create');
    Route::get('quotation/index/{id}', [QuotationController::class, 'index'])->name('quotation.index');
    Route::delete('/quotation/delete-selected/{ids}', [QuotationController::class, 'destroySelected'])->name('quotation.destroySelected');

    Route::resource('invoice', InvoiceController::class);
    Route::get('invoice/create/{id}', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::get('invoice/index/{id}', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::post('/invoice/register/{id}', [InvoiceController::class, 'registerPayment'])->name('invoice.registerPayment');
    Route::delete('/invoice/delete-selected/{ids}', [InvoiceController::class, 'destroySelected'])->name('invoice.destroySelected');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
