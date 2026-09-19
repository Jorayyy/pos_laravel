<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OwnerController;
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'active'])->name('dashboard');

Route::get('/pos', [PosController::class, 'index'])->middleware(['auth', 'active'])->name('pos');

Route::get('/receipt/{sale}', [ReceiptController::class, 'show'])->name('receipt')->middleware('auth');

Route::middleware(['auth', 'active', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('owners', OwnerController::class);
    Route::resource('pets', PetController::class);

    Route::resource('product-categories', ProductCategoryController::class)->parameters([
        'product-categories' => 'productCategory',
    ]);
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');

    Route::resource('service-categories', ServiceCategoryController::class)->parameters([
        'service-categories' => 'serviceCategory',
    ]);
    Route::resource('services', ServiceController::class);

    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/stock-in', [InventoryController::class, 'stockIn'])->name('inventory.stock-in');
    Route::post('inventory/stock-out', [InventoryController::class, 'stockOut'])->name('inventory.stock-out');
    Route::post('inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::get('inventory/product/{product}', [InventoryController::class, 'productHistory'])->name('inventory.product-history');

    Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::post('sales/{sale}/void', [SaleController::class, 'voidSale'])->name('sales.void');
    Route::post('sales/{sale}/refund', [SaleController::class, 'refund'])->name('sales.refund');

    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('daily-sales', [ReportController::class, 'dailySales'])->name('daily-sales');
        Route::get('weekly-sales', [ReportController::class, 'weeklySales'])->name('weekly-sales');
        Route::get('monthly-sales', [ReportController::class, 'monthlySales'])->name('monthly-sales');
        Route::get('sales-by-product', [ReportController::class, 'salesByProduct'])->name('sales-by-product');
        Route::get('sales-by-service', [ReportController::class, 'salesByService'])->name('sales-by-service');
        Route::get('sales-by-cashier', [ReportController::class, 'salesByCashier'])->name('sales-by-cashier');
        Route::get('inventory', [ReportController::class, 'inventoryReport'])->name('inventory');
        Route::get('low-stock', [ReportController::class, 'lowStockReport'])->name('low-stock');
        Route::get('expiring', [ReportController::class, 'expiringReport'])->name('expiring');
        Route::get('veterinary', [ReportController::class, 'veterinaryReport'])->name('veterinary');
    });

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    Route::get('receipts/{sale}', [ReceiptController::class, 'show'])->name('receipts.show');
    Route::get('receipts/{sale}/pdf', [ReceiptController::class, 'pdf'])->name('receipts.pdf');
});
