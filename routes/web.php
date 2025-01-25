<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;



require __DIR__.'/auth.php';

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/profiles', 'profile')->name('profiles');//added par yassine page profile

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
        Route::post('/add-to-cart', 'addToCart')->name('add.to.cart');

    });
    Route::controller(PaymentController::class)->group(function () {
        Route::get('/checkout', 'checkout')->name('checkout');
        Route::post('/payment', 'payment')->name('payment');
        Route::get('/pay', 'pay')->name('pay');
        Route::post('/charge', 'charge')->name('charge');

    });
});

Route::controller(CourseController::class)->group(function () {
    Route::get('/singlecourse/{id}', 'singlecourse')->name('singlecourse');
    Route::get('/courses', 'index')->name('courses');
});

Route::group([ 'middleware' => ['auth','verified']], function () {
    Route::controller(ProductController::class)->group(function () {
        Route::get('/singleproduct/{id}', 'singleproduct')->name('singleproduct');

    });
});

Route::controller(AdminController::class)->group(function () {
    Route::get('adminindex', 'index')->name('adminindex');
    Route::get('admincourses', 'courses')->name('admincourses');
    Route::post('addcourse', 'addcourse')->name('addcourse');
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



