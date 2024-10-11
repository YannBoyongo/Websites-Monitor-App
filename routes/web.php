<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});


Route::get('/websites/create', [WebsiteController::class, 'create'])->name('websites.create');
Route::get('/websites/edit/{website}', [WebsiteController::class, 'edit'])->name('websites.edit');
Route::get('/websites', [WebsiteController::class, 'index'])->name('websites.index');
Route::post('/websites', [WebsiteController::class, 'store'])->name('websites.store');
Route::put('/websites/{id}', [WebsiteController::class, 'update'])->name('websites.update');
Route::patch('/websites/{id}/toggle', [WebsiteController::class, 'toggleWebsiteStatus'])->name('websites.toggle');