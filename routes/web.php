<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Front page. Until the shop home (Figma B1) is built:
// guests → login form, team → orders, buyers → "shop opening soon".
Route::get('/', function () {
    $user = request()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    return $user->isStaff()
        ? redirect()->route('admin.orders.index')
        : view('shop.coming-soon');
})->name('home');

// After login everyone lands here: the team goes to the orders dashboard,
// buyers go back to the shop.
Route::get('/dashboard', function () {
    return request()->user()->isStaff()
        ? redirect()->route('admin.orders.index')
        : redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// Shop admin (admin + sellers). Each action is also checked by OrderPolicy.
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
