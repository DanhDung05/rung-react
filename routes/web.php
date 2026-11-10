<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\DestinationController;
use App\Http\Controllers\Frontend\FaqController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\TourController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitemapController;

/*
|--------------------------------------------------------------------------
| Web Routes — Frontend
|--------------------------------------------------------------------------
*/


// Sitemap (ngoài prefix, không có middleware web)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap')
    ->withoutMiddleware(['web']);
// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Tour
Route::prefix('tour')->name('tours.')->group(function () {
    Route::get('/', [TourController::class, 'index'])->name('index');
    Route::get('/danh-muc/{slug}', [TourController::class, 'category'])->name('category');
    Route::get('/{slug}', [TourController::class, 'show'])->name('show');
});

// Destination
Route::prefix('diem-den')->name('destinations.')->group(function () {
    Route::get('/', [DestinationController::class, 'index'])->name('index');
    Route::get('/{slug}', [DestinationController::class, 'show'])->name('show');
});

// Blog
Route::prefix('bai-viet')->name('posts.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/danh-muc/{slug}', [PostController::class, 'category'])->name('category');
    Route::get('/tag/{slug}', [PostController::class, 'tag'])->name('tag');
    Route::get('/{slug}', [PostController::class, 'show'])->name('show');
});

// Trang tĩnh — Giới thiệu
Route::get('/gioi-thieu', [PageController::class, 'about'])->name('about');

// FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// Liên hệ
Route::get('/lien-he', [ContactController::class, 'show'])->name('contact');
Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store');

// Tìm kiếm
Route::get('/tim-kiem', [SearchController::class, 'index'])->name('search');

// Trang tĩnh chung (đặt CUỐI để không conflict)
Route::get('/trang-tinh/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::post('/tour/{tour}/review', [\App\Http\Controllers\Frontend\ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('tours.review.store');