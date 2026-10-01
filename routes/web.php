<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MagazijnController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Magazijn routes
    Route::get('/magazijn', [MagazijnController::class, 'index'])->name('magazijn.overzicht');
    Route::get('/magazijn/levering/{productId}', [MagazijnController::class, 'leveringInfo'])->name('magazijn.levering-info');
    Route::get('/magazijn/allergenen/{productId}', [MagazijnController::class, 'allergenenInfo'])->name('magazijn.allergenen-info');
});

require __DIR__.'/auth.php';
