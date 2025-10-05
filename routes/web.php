<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\BanomController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// News
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/daerah/{slug}', [NewsController::class, 'area'])->name('news.area');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/cari', [NewsController::class, 'search'])->name('search');

// Categories
Route::get('/kategori/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Areas
Route::get('/daerah/{slug}', [AreaController::class, 'show'])->name('area.show');

// Banom
Route::get('/badan-otonom', [BanomController::class, 'index'])->name('banom.index');
Route::get('/badan-otonom/{slug}', [BanomController::class, 'show'])->name('banom.show');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
