<?php

use App\Http\Controllers\FavouritesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchHistoryController;
use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    Route::delete('/favorites/{id}', [FavouritesController::class, 'destroy']);

    Route::get('/search-history', [SearchHistoryController::class, 'index']);
    Route::post('/search-history', [SearchHistoryController::class, 'store']);
    Route::delete('/historyItem/{itemId}', [SearchHistoryController::class, 'destroy']);
});

Route::get('/', function (Request $request) {
    Log::info('Session Data:', [
        'currentWeather' => $request->session()->get('currentWeather', 'No data'),
        'sixteenDayForecast' => $request->session()->get('sixteenDayForecast', 'No data'),
    ]);
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
