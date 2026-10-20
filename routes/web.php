<?php

use App\Http\Controllers\ManageController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PortfolioPageController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Create and edit. whereUuid() makes a bad id a clean 404 instead of a database error.
Route::get('/create', [PortfolioController::class, 'create'])->name('portfolios.create');
Route::post('/portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
Route::get('/portfolios/{portfolio}/edit', [PortfolioController::class, 'edit'])->whereNumber('portfolio')->name('portfolios.edit');
Route::put('/portfolios/{portfolio}', [PortfolioController::class, 'update'])->whereNumber('portfolio')->name('portfolios.update');
Route::delete('/portfolios/{portfolio}', [ManageController::class, 'destroy'])->whereNumber('portfolio')->name('portfolios.destroy');

// Templates and preview
Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::patch('/portfolios/{portfolio}/template', [TemplateController::class, 'update'])->whereNumber('portfolio')->name('portfolios.template');
Route::get('/portfolio/{portfolio}', [PortfolioPageController::class, 'show'])->whereNumber('portfolio')->name('portfolios.show');

// Manage
Route::get('/manage', [ManageController::class, 'index'])->name('manage');