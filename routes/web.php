<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SubPangkalanController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Sub Pangkalan
    Route::get('/sub-pangkalan', [AdminController::class, 'subPangkalanIndex'])->name('sub-pangkalan.index');
    Route::get('/sub-pangkalan/create', [AdminController::class, 'subPangkalanCreate'])->name('sub-pangkalan.create');
    Route::post('/sub-pangkalan', [AdminController::class, 'subPangkalanStore'])->name('sub-pangkalan.store');
    Route::get('/sub-pangkalan/{subPangkalan}/edit', [AdminController::class, 'subPangkalanEdit'])->name('sub-pangkalan.edit');
    Route::put('/sub-pangkalan/{subPangkalan}', [AdminController::class, 'subPangkalanUpdate'])->name('sub-pangkalan.update');
    Route::post('/sub-pangkalan/{subPangkalan}/toggle-status', [AdminController::class, 'subPangkalanToggleStatus'])->name('sub-pangkalan.toggle-status');
    Route::delete('/sub-pangkalan/{subPangkalan}', [AdminController::class, 'subPangkalanDestroy'])->name('sub-pangkalan.destroy');
    Route::get('/sub-pangkalan/{subPangkalan}', [AdminController::class, 'subPangkalanDetail'])->name('sub-pangkalan.detail');
    Route::post('/sub-pangkalan/{subPangkalan}/reset-password', [AdminController::class, 'subPangkalanResetPassword'])->name('sub-pangkalan.reset-password');

    // Stock (FIFO)
    Route::get('/stock', [AdminController::class, 'stockIndex'])->name('stock.index');
    Route::get('/stock/create', [AdminController::class, 'stockCreate'])->name('stock.create');
    Route::post('/stock', [AdminController::class, 'stockStore'])->name('stock.store');
    Route::get('/stock/{stockLpg}/edit', [AdminController::class, 'stockEdit'])->name('stock.edit');
    Route::put('/stock/{stockLpg}', [AdminController::class, 'stockUpdate'])->name('stock.update');
    Route::get('/stock/batch/{batch}/edit', [AdminController::class, 'stockBatchEdit'])->name('stock.batch.edit');
    Route::put('/stock/batch/{batch}', [AdminController::class, 'stockBatchUpdate'])->name('stock.batch.update');
    Route::delete('/stock/batch/{batch}', [AdminController::class, 'stockBatchDestroy'])->name('stock.batch.destroy');
    Route::post('/stock/outflow/clear/{tabung_type}', [AdminController::class, 'clearOutflowHistory'])->name('stock.outflow.clear');



    // Monitoring Sub Pangkalan
    Route::get('/monitoring', [AdminController::class, 'monitoringIndex'])->name('monitoring.index');
    Route::get('/monitoring/{subPangkalan}', [AdminController::class, 'monitoringDetail'])->name('monitoring.detail');
    Route::post('/sub-pangkalan-transaction/{distribution}/confirm-return', [AdminController::class, 'confirmReturnKosong'])->name('sub-pangkalan-transaction.confirm-return');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Sales (Admin)
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

    // Profile Admin
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'profileUpdate'])->name('profile.update');
    Route::delete('/profile/photo', [AdminController::class, 'profileDeletePhoto'])->name('profile.delete-photo');
    Route::delete('/profile/login-logo', [AdminController::class, 'profileDeleteLoginLogo'])->name('profile.delete-login-logo');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/export-pdf', [AdminController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-excel', [AdminController::class, 'exportExcel'])->name('reports.export-excel');

    // Penjualan Langsung ke Pembeli
    Route::get('/penjualan', [AdminController::class, 'penjualanIndex'])->name('penjualan.index');
    Route::get('/penjualan/create', [AdminController::class, 'penjualanCreate'])->name('penjualan.create');
    Route::post('/penjualan', [AdminController::class, 'penjualanStore'])->name('penjualan.store');
    Route::delete('/penjualan/clear', [AdminController::class, 'penjualanClear'])->name('penjualan.clear');
    Route::delete('/penjualan/{id}', [AdminController::class, 'penjualanDestroy'])->name('penjualan.destroy');
    Route::get('/penjualan/check-quota', [AdminController::class, 'checkCustomerQuota'])->name('penjualan.check-quota');
    Route::get('/penjualan/search-customers', [AdminController::class, 'searchCustomers'])->name('penjualan.search-customers');
});

// Sub Pangkalan routes
Route::middleware(['auth', 'sub_pangkalan'])->prefix('sub-pangkalan')->name('sub-pangkalan.')->group(function () {
    Route::get('/dashboard', [SubPangkalanController::class, 'dashboard'])->name('dashboard');

    // Data Pelanggan
    Route::get('/customers', [SubPangkalanController::class, 'customerIndex'])->name('customers.index');
    Route::get('/customers/create', [SubPangkalanController::class, 'customerCreate'])->name('customers.create');
    Route::post('/customers', [SubPangkalanController::class, 'customerStore'])->name('customers.store');
    Route::get('/customers/{customer}', [SubPangkalanController::class, 'customerShow'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [SubPangkalanController::class, 'customerEdit'])->name('customers.edit');
    Route::put('/customers/{customer}', [SubPangkalanController::class, 'customerUpdate'])->name('customers.update');
    Route::delete('/customers/{customer}', [SubPangkalanController::class, 'customerDestroy'])->name('customers.destroy');
    // Jual ke pelanggan
    Route::get('/sell', [SubPangkalanController::class, 'sellCreate'])->name('sell.create');
    Route::post('/sell', [SubPangkalanController::class, 'sellStore'])->name('sell.store');
    // Tukar tabung kosong
    Route::get('/exchange', [SubPangkalanController::class, 'exchangeCreate'])->name('exchange.create');
    Route::post('/exchange', [SubPangkalanController::class, 'exchangeStore'])->name('exchange.store');
    // Riwayat
    Route::get('/history', [SubPangkalanController::class, 'history'])->name('history');
    Route::delete('/history/clear', [SubPangkalanController::class, 'clearHistory'])->name('history.clear');
    // Konfirmasi Penerimaan
    Route::post('/sub-pangkalan-transaction/{distribution}/confirm', [SubPangkalanController::class, 'confirmReceive'])->name('sub-pangkalan-transaction.confirm');
    // Profil Saya
    Route::get('/profile', [SubPangkalanController::class, 'profile'])->name('profile');
    Route::put('/profile', [SubPangkalanController::class, 'profileUpdate'])->name('profile.update');
    Route::delete('/profile/photo', [SubPangkalanController::class, 'profileDeletePhoto'])->name('profile.delete-photo');
});

// Home redirect
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('sub-pangkalan.dashboard');
    }
    return redirect()->route('login');
});
