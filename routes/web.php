<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;

// Rute User Area
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/{event}', [EventController::class,'show'])->name('events.show');
Route::get('/checkout', [EventController::class,'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

Route::post('/logout', function() {
    return redirect('/');
})->name('logout');

// login admin
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');
// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Login Admin
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/transactions', [DashboardController::class, 'indexTransaction'])
            ->name('transactions.index');

        Route::resource('events', EventAdminController::class);

        Route::resource('categories', CategoryController::class)
            ->except(['index', 'show']);

        Route::resource('partners', PartnerController::class)
            ->except(['show']);
    });
});