<?php

use App\Models\User_interactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;

Route::get('/', [NewsController::class, 'index'])->name('home');

Route::get('/other-news', [NewsController::class, 'other_news'])->name('other_news');

Route::get('/search', [NewsController::class, 'search'])->name('search');

Route::get('/login', [AuthController::class, 'view_login'])->name('login');

Route::get('/Unauthorized', [AuthController::class, 'unauthenticated'])->name('unauthenticated');

Route::middleware(['guest'])->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/check-like-status/{news_id}', function ($news_id) {
        $liked = User_interactions::where('news_id', $news_id)
            ->where('user_id', Auth::id())
            ->where('interaction_type', 'like')
            ->exists();

        return response()->json(['liked' => $liked]);
    });

    Route::post('/interact', [NewsController::class, 'interact']);

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

    Route::post('/edit-profile', [AuthController::class, 'edit_profile'])->name('edit.profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});