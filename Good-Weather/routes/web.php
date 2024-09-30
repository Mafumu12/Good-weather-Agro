<?php

use App\Http\Controllers\FavouritesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchHistoryController;
use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/favorites', [FavouritesController::class, 'store']);
    Route::get('/favorites', [FavouritesController::class, 'index']);
    Route::get('/search-history', [SearchHistoryController::class, 'index']);
    Route::post('/search-history', [SearchHistoryController::class, 'store']);
});

Route::get('/', function (Request $request) {
    return Inertia::render('Weather', [
        'currentWeather' => $request->session()->get('currentWeather', []),
        'sixteenDayForecast' => $request->session()->get('sixteenDayForecast', []),
    ]);
})->name('weather-home');
Route::get('/welcome', function () {
    return Inertia::render('Welcome');
});
Route::post('/city', [WeatherController::class, 'getCurrentWeather']);

require __DIR__ . '/auth.php';
