<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;


require __DIR__.'/auth.php';

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/policy', 'policy')->name('policy');
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/contact', 'contact')->name('contact');
});

Route::group([ 'middleware' => ['auth','verified']], function () {
    Route::controller(ShopController::class)->group(function () {
        Route::get('/shop', 'index')->name('shop');
        Route::get('/cart', 'cart')->name('cart');
        Route::get('/wishlist', 'wishlist')->name('wishlist');
    });
    Route::controller(CourseController::class)->group(function () {
        Route::get('/courses', 'index')->name('courses');
    });

});





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'user', 'middleware' => ['verified', '2fa.verify', 'role:user|subscriber|admin', 'PreventBackHistory', 'checkNotAuth']], function () {
    // USER DASHBOARD ROUTES
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/dashboard/favorite', [UserDashboardController::class, 'favorite']);
    Route::post('/dashboard/favoritecustom', [UserDashboardController::class, 'favoriteCustom']);
    // USER TEMPLATE ROUTES
    Route::controller(TemplateController::class)->group(function () {
        Route::get('/templates/custom-template/{code}', 'viewCustomTemplate');
        Route::get('/templates/original-template/{slug}', 'viewOriginalTemplate');
    });
});



