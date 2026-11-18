<?php

use App\Http\Controllers\LeverancierBeheerController;
use App\Http\Controllers\LeverancierController;
use App\Http\Controllers\LeveringController;
use App\Http\Controllers\MagazijnController;
use App\Http\Controllers\ProfileController;
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

    // Magazijn routes (Opdracht 1 - bestaand, blijft werken)
    Route::get('/magazijn', [MagazijnController::class, 'index'])->name('magazijn.overzicht');
    Route::get('/magazijn/levering/{productId}', [MagazijnController::class, 'leveringInfo'])->name('magazijn.levering-info');
    Route::get('/magazijn/allergenen/{productId}', [MagazijnController::class, 'allergenenInfo'])->name('magazijn.allergenen-info');

    // Leverancier routes (Opdracht 2 - Userstory 1)
    Route::get('/leveranciers', [LeverancierController::class, 'overzicht'])->name('leverancier.overzicht');
    Route::get('/leveranciers/{leverancierId}/producten', [LeverancierController::class, 'geleverdeProducten'])->name('leverancier.geleverde-producten');

    Route::prefix('leveranciers/beheer')->name('leverancier.beheer.')->middleware('can:wijzig-leveranciers')->group(function () {
        Route::get('/', [LeverancierBeheerController::class, 'index'])->name('index');
        Route::get('/{leverancierId}', [LeverancierBeheerController::class, 'show'])->whereNumber('leverancierId')->name('show');
        Route::get('/{leverancierId}/wijzig', [LeverancierBeheerController::class, 'edit'])->whereNumber('leverancierId')->name('edit');
        Route::patch('/{leverancierId}', [LeverancierBeheerController::class, 'update'])->whereNumber('leverancierId')->name('update');
    });

    // Levering routes (Opdracht 2 - Userstory 2)
    Route::get('/leveranciers/{leverancierId}/producten/{productId}/levering', [LeveringController::class, 'formulier'])->name('levering.formulier');
    Route::post('/leveranciers/{leverancierId}/producten/{productId}/levering', [LeveringController::class, 'opslaan'])->name('levering.opslaan');
});

require __DIR__.'/auth.php';
