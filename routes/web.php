<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// ─── Installation Routes ─────────────────────────────────────────────────────

Route::get('/install', [InstallController::class, 'show'])->name('install.index');
Route::post('/install', [InstallController::class, 'install'])->name('install.perform');
Route::get('/install/success', [InstallController::class, 'success'])->name('install.success');

// ─── Public Routes ───────────────────────────────────────────────────────────

Route::middleware(['web', 'track.visitor'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::prefix('match')->name('match.')->group(function () {
        Route::get('/{matchId}', [MatchController::class, 'detail'])->name('detail');
        Route::get('/{matchId}/stream', [MatchController::class, 'stream'])->name('stream');
    });

    // AJAX / API endpoints for live tab data
    Route::prefix('api/match')->name('api.match.')->middleware('throttle:120,1')->group(function () {
        Route::get('/{matchId}/live', [MatchController::class, 'liveData'])->name('live');
        Route::get('/{matchId}/standings', [MatchController::class, 'standings'])->name('standings');
        Route::get('/{matchId}/h2h', [MatchController::class, 'h2h'])->name('h2h');
        Route::get('/{matchId}/highlights', [MatchController::class, 'highlights'])->name('highlights');
    });

    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
    Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
});

// ─── Auth Routes ──────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.auth'])->group(function () {

    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Featured Matches
    Route::resource('featured', Admin\FeaturedMatchController::class)->parameters(['featured' => 'featured']);

    // Advertisements
    Route::resource('advertisements', Admin\AdvertisementController::class);
    Route::post('advertisements/{advertisement}/toggle', [Admin\AdvertisementController::class, 'toggle'])->name('advertisements.toggle');

    // CMS Pages
    Route::resource('pages', Admin\SitePageController::class);

    // Users
    Route::resource('users', Admin\UserController::class);

    // Settings
    Route::get('settings', [Admin\SiteSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [Admin\SiteSettingController::class, 'update'])->name('settings.update');
    Route::get('settings/seo', [Admin\SiteSettingController::class, 'seo'])->name('settings.seo');
    Route::post('settings/seo', [Admin\SiteSettingController::class, 'updateSeo'])->name('settings.seo.update');

    // Activity Logs
    Route::get('logs', [Admin\ActivityLogController::class, 'index'])->name('logs.index');
});