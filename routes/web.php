<?php

use App\Models\News;
use App\Models\User_interactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminController;

Route::get('/', [NewsController::class, 'index']);

Route::get('/login', [AuthController::class, 'index'])->name('login.view');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/interact', [NewsController::class, 'interact']);

Route::get('/search', [NewsController::class, 'search'])->name('search');

Route::get('/check-like-status/{news_id}', function ($news_id) {
    $liked = User_interactions::where('news_id', $news_id)
        ->where('user_id', Auth::id())
        ->where('interaction_type', 'like')
        ->exists();

    return response()->json(['liked' => $liked]);
});

Route::middleware(['auth'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index']);

    Route::post('/News/store', [NewsController::class, 'store'])->name('News.store');

    Route::delete('/News/{id}/destroy', [NewsController::class, 'destroy'])->name('News.destroy');

});

Route::post('/News/update', [NewsController::class, 'update'])->name('News.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');