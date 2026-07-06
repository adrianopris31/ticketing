<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::get('/ticketing', [EventController::class, 'index'])->name('ticketing');
Route::post('/events/{event}/book', [BookingController::class, 'store']);
Route::get('/events/{id}/bookings', [BookingController::class, 'show']);

require __DIR__.'/settings.php';
