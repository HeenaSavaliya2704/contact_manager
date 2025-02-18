<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactImportController;

Route::get('/', function () {
    return redirect()->route('contacts.index');
});

// Authentication Routes (Handled by Breeze)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);


Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

// Contacts CRUD Routes
Route::middleware('auth')->group(function () {
    Route::resource('contacts', ContactController::class);

    Route::post('contacts/import', [ContactImportController::class, 'import'])->name('contacts.import.process');
    Route::get('contacts/export-xml', [ContactController::class, 'show'])->name('contacts.export.xml');
});
