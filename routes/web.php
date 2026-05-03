<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Staff\OrderController as StaffOrderController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use Illuminate\Support\Facades\Route;

// Redirect root to menu
Route::get('/', function () {
    return redirect()->route('customer.menu');
});

// Customer Routes
Route::get('/menu/{table?}', [MenuController::class, 'index'])->name('customer.menu');
Route::get('/cart', [CustomerOrderController::class, 'cart'])->name('customer.cart');
Route::post('/cart/add/{product}', [CustomerOrderController::class, 'addToCart'])->name('customer.cart.add');
Route::post('/cart/remove/{product}', [CustomerOrderController::class, 'removeFromCart'])->name('customer.cart.remove');
Route::delete('/cart/clear', [CustomerOrderController::class, 'clearCart'])->name('customer.cart.clear');
Route::post('/order', [CustomerOrderController::class, 'store'])->middleware('throttle:5,1')->name('customer.order.store');
Route::get('/track/{reference}', [CustomerOrderController::class, 'track'])->name('customer.track');
Route::post('/reviews/{product}', [CustomerReviewController::class, 'store'])->middleware('throttle:3,1')->name('customer.reviews.store');

// Dashboard Redirection
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->role === 'staff') {
        return redirect()->route('staff.orders.index');
    }
    return redirect()->route('customer.menu');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{product}/{code}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Staff Routes
Route::middleware(['auth'])->prefix('staff')->name('staff.')->middleware('staff')->group(function () {
    Route::get('/orders', [StaffOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{reference}', [StaffOrderController::class, 'update'])->name('orders.update');
    Route::get('/history', [StaffOrderController::class, 'history'])->name('orders.history');
    Route::get('/report', [StaffOrderController::class, 'generateReport'])->name('orders.report');
    Route::get('/cashier', [StaffOrderController::class, 'cashier'])->name('orders.cashier');
    Route::patch('/orders/{reference}/pay', [StaffOrderController::class, 'markAsPaid'])->name('orders.markAsPaid');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
