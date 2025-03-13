<?php

use App\Models\User;
use App\Models\User_interactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\RecommendationController;

// login google
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
        ]
    );

    Auth::login($user);

    return redirect('/');
});
// login google

Route::get('/', [NewsController::class, 'index'])->name('home');

Route::get('/other-news', [NewsController::class, 'other_news'])->name('other_news');

Route::get('/search', [NewsController::class, 'search'])->name('search');

Route::get('/login', [AuthController::class, 'view_login'])->name('login');

Route::get('/Unauthorized', [AuthController::class, 'unauthenticated'])->name('unauthenticated');

Route::get('/news/{params}', [NewsController::class, 'news'])->name('news');

Route::middleware(['guest'])->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/check-like-status/{news_title}', function ($news_title) {
        if (!Auth::check()) {
            return response()->json(['liked' => false]);
        }

        $liked = User_interactions::where('user_id', Auth::id())
            ->where('news_title', $news_title)
            ->where('interaction_type', 'like')
            ->exists();

        return response()->json(['liked' => $liked]);
    });

    Route::get('/recommend/{userId}', [RecommendationController::class, 'recommend']);

    Route::post('/interact', [NewsController::class, 'interact']);

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

    Route::post('/edit-profile', [AuthController::class, 'edit_profile'])->name('edit.profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});