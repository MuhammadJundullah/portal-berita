<?php

use App\Models\kaffah;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KaffahController;

Route::get('/', [KaffahController::class, 'index']);

Route::get('/login', [AuthController::class, 'index'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::post('/kaffah/store', [KaffahController::class, 'store'])->name('kaffah.store');
    Route::delete('/kaffah/{id}/destroy', [KaffahController::class, 'destroy'])->name('kaffah.destroy');
});

Route::post('/kaffah/update', [KaffahController::class, 'update'])->name('kaffah.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');