<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;


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
        Route::post('/add-to-cart', 'addToCart')->name('add.to.cart');
        Route::post('/remove-from-cart', 'removeFromCart')->name('remove.from.cart');



    });

    Route::controller(PaymentController::class)->group(function () {
        Route::get('/success','successPage')->name('checkout.success');
        Route::get('/cancel','cancelPage')->name('checkout.cancel');

        Route::get('/checkout', 'checkout')->name('checkout');
        Route::post('/payment', 'payment')->name('payment');
        /* Route::post('/pay', 'pay')->name('pay'); */
        Route::post('/charge', 'charge')->name('charge');
        Route::get('/sync-payment',  'syncPayment')->name('syncPayment');
        //Route::get('/success', 'success')->name('success');

    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/singleproduct/{id}', 'singleproduct')->name('singleproduct');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('/profiles', 'profile')->name('profiles');//added par yassine page profile

    });
});

Route::group(['middleware' => ['auth', 'verified', 'role:student']], function () {
    //dashboard routes
    Route::get('/dashboard/{any?}', [DashboardController::class, 'index'])
    ->where('any', '.*')
    ->name('dashboard');
    //api routes
    Route::get('/api/courses', [DashboardController::class, 'getCourses'])->name('api.courses');
    Route::get('/api/course/{id}', [DashboardController::class, 'getCourse'])->name('api.course');
    Route::get('/api/courses/{id}', [DashboardController::class, 'streamVideo'])->name('api.course.show');
    Route::get('/storage/private-cover/{filename}', [DashboardController::class, 'serveCover'])
    ->name('cover.access');
    
});

Route::controller(CourseController::class)->group(function () {

    Route::get('/singlecourse/{id}', 'singlecourse')->name('singlecourse');
    Route::get('/courses', 'index')->name('courses');
});

Route::controller(AdminController::class)->group(function () {

    Route::get('adminindex', 'index')->name('adminindex');
    Route::get('adminfinanceindex', 'finance')->name('adminfinanceindex');
    Route::get('usersmanagement', 'usersManagement')->name('usersManagement');
    Route::get('admincourses', 'courses')->name('admincourses');
    Route::post('addcourse', 'addcourse')->name('addcourse');
    // Route pour mettre à jour un cours
    Route::post('/admin/course/update/{id}', 'updateCourse')->name('update.course');

    // Route pour supprimer un cours
    Route::delete('/admin/course/delete/{id}','deleteCourse')->name('delete.course');

});


/* Route::middleware('auth')->group(function () {
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
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/course-content/{id}', [CourseController::class, 'show'])->name('course.content');
}); */






