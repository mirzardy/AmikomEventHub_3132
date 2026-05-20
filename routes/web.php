<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;


// Rute User Area
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/{event}', [EventController::class,'show'])->name('events.show');
Route::get('/checkout', [EventController::class,'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');
Route::post('/logout', function() { return redirect('/'); })->name('logout');

use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/transactions', [DashboardController::class,'indexTransaction'])->name('transactions.index');
    Route::resource('events', EventAdminController::class);
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
    Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/create', [PartnerController::class, 'create'])->name('partners.create');
    Route::post('/partners', [PartnerController::class, 'store'])->name('partners.store');
    Route::get('/partners/{partner}/edit', [PartnerController::class, 'edit'])->name('partners.edit');
    Route::put('/partners/{partner}', [PartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partners/{partner}', [PartnerController::class, 'destroy'])->name('partners.destroy');
});
