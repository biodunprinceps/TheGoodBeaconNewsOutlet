<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SearchController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('article.show');
Route::get('/category/{slug}', [ArticleController::class, 'category'])->name('category.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Debug route to check APP_KEY - REMOVE AFTER DEBUGGING
Route::get('/debug-app-key', function () {
    return response()->json([
        'app_key_set' => !empty(config('app.key')),
        'app_key_prefix' => substr(config('app.key'), 0, 20),
        'app_key_length' => strlen(config('app.key')),
        'env_app_key_prefix' => substr(env('APP_KEY'), 0, 20),
        'cached_config' => app()->configurationIsCached(),
        'cache_driver' => config('cache.default'),
    ]);
});
