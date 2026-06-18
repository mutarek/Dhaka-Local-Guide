<?php

use App\Http\Controllers\AdvertisementClickController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/search', SearchController::class)->middleware('throttle:search')->name('search');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/ads/{advertisement}/click', AdvertisementClickController::class)->middleware('throttle:ad-click')->name('ads.click');
Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/preview/{post:slug}', [PostController::class, 'preview'])->middleware('signed')->name('posts.preview');
Route::get('/{post:slug}', [PostController::class, 'show'])->name('posts.show');
