<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PortfolioPageController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Anyone can VIEW a portfolio by its link
Route::get('/portfolio/{portfolio}', [PortfolioPageController::class, 'show'])->whereNumber('portfolio')->name('portfolios.show');

// Sign in with Google (only for visitors who are not signed in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [GoogleAuthController::class, 'show'])->name('login');
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
});

// Everything below needs a signed-in user (the controllers also check ownership)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

    Route::get('/create', [PortfolioController::class, 'create'])->name('portfolios.create');
    Route::post('/portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
    Route::get('/portfolios/{portfolio}/edit', [PortfolioController::class, 'edit'])->whereNumber('portfolio')->name('portfolios.edit');
    Route::put('/portfolios/{portfolio}', [PortfolioController::class, 'update'])->whereNumber('portfolio')->name('portfolios.update');
    Route::delete('/portfolios/{portfolio}', [ManageController::class, 'destroy'])->whereNumber('portfolio')->name('portfolios.destroy');

    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::patch('/portfolios/{portfolio}/template', [TemplateController::class, 'update'])->whereNumber('portfolio')->name('portfolios.template');

    Route::get('/manage', [ManageController::class, 'index'])->name('manage');
});