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
use App\Http\Controllers\UserExamsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminExamsController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ContactController;






require __DIR__.'/auth.php';

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/policy', 'policy')->name('policy');
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/about', 'about')->name('about');
});

Route::controller(ContactController::class)->group(function () {
    Route::post('/contact/submit', 'submitContactForm')->name('contact.submit');
    Route::get('/admin/inbox', 'index')->name('admin.contacts.index');
    Route::delete('/admin/contacts/{id}', 'destroy')->name('admin.contacts.destroy');
});

Route::controller(CourseController::class)->group(function () {
    Route::get('/singlecourse/{id}', 'singlecourse')->name('singlecourse');
    Route::get('/courses', 'index')->name('courses');
});

Route::group([ 'middleware' => ['auth','verified']], function () {

    Route::controller(ShopController::class)->group(function () {
        Route::get('/shop', 'index')->name('shop');
        Route::get('/cart', 'cart')->name('cart');
        Route::get('/wishlist', 'wishlist')->name('wishlist');
        Route::post('/add-to-wishlist', 'addToWishlist')->name('add.to.wishlist');
        Route::post('/remove-from-wishlist', 'removeFromWishlist')->name('remove.from.wishlist');
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
    });
    Route::controller(ProductController::class)->group(function () {
        Route::get('/singleproduct/{id}', 'singleproduct')->name('singleproduct');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'profile')->name('profile');//added par yassine page profile
    });
});

Route::group(['middleware' => ['auth', 'verified', 'role:student']], function () {
    // Dashboard route
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard/{any?}', 'index')->where('any', '.*')->name('dashboard');
        // API routes under '/api' prefix
        Route::prefix('api')->group(function () {
            Route::get('/courses', 'getCourses')->name('api.courses');
            Route::get('/course/{id}', 'getCourse')->whereNumber('id')->name('api.course');
            Route::get('/courses/{id}', 'streamVideo')->whereNumber('id')->name('api.course.show');
        });
        // Secure storage access route
        Route::get('/storage/private-cover/{filename}', 'serveCover')->name('cover.access');

    });
    Route::controller(UserExamsController::class)->group(function () {
        Route::prefix('exams')->group(function () {
            Route::post('/start/{exam_id}', 'startExam')->whereNumber('exam_id')->name('exam.start');
            Route::get('/{exam_id}', 'showExam')->whereNumber('exam_id')->name('user.exams.show');
            Route::post('/{exam_id}/submit', 'submitExam')->whereNumber('exam_id')->name('user.exams.submit');
            Route::get('/{exam_id}/results', 'examResults')->whereNumber('exam_id')->name('user.exams.results');
            Route::get('/history', 'userExamHistory')->name('user.exams.history');
            Route::get('/{session_id}/question', 'getNextQuestion')->whereNumber('session_id')->name('exam.question');
            Route::post('/{session_id}/answer', 'submitAnswer')->whereNumber('session_id')->name('exam.answer');
            Route::post('/{session_id}/complete', 'markExamAsCompleted')->whereNumber('session_id')->name('exam.complete');
        });
        // Routes liées à la progression vidéo
        Route::prefix('video/progress')->group(function () {
            Route::post('/update', 'updateProgress')->name('video.progress.update');
            Route::get('/check/{course_id}', 'checkProgress')->whereNumber('course_id')->name('video.progress.check');
            Route::post('/complete', 'markAsCompleted')->name('video.progress.complete');
            Route::post('/reset', 'resetProgress')->name('video.progress.reset');
        });
    });
});

Route::controller(AdminController::class)->group(function () {
    Route::get('adminindex', 'index')->name('adminindex');
    Route::get('adminfinanceindex', 'finance')->name('adminfinanceindex');
    Route::get('usersmanagement', 'usersManagement')->name('usersManagement');
    Route::get('admincourses', 'addcourses')->name('admincourses');
    Route::get('removecourses', 'removecourses')->name('removecourses');
    Route::post('addcourse', 'addcourse')->name('addcourse');
    // Route pour mettre à jour un cours
    Route::post('/admin/course/update/{id}', 'updateCourse')->name('update.course');
    // Route pour supprimer un cours
    Route::delete('/admin/course/delete/{id}','deleteCourse')->name('delete.course');

});

Route::middleware([])->group(function () {

    Route::prefix('admin/exams')->controller(AdminExamsController::class)->group(function () {
        // Gestion des examens
        Route::get('/', 'listExams')->name('admin.exams');
        Route::get('/create', 'createExam')->name('admin.exams.create');
        Route::post('/store', 'storeExam')->name('admin.exams.store');
        Route::get('/{id}/edit', 'editExam')->whereNumber('id')->name('admin.exams.edit');
        Route::post('/{id}/update', 'updateExam')->whereNumber('id')->name('admin.exams.update');
        Route::delete('/{id}/delete', 'deleteExam')->whereNumber('id')->name('admin.exams.delete');
        Route::put('/{id}/toggle', 'toggleExamStatus')->whereNumber('id')->name('admin.exams.toggle');
        // Gestion des questions d'un examen
        Route::prefix('{exam_id}/questions')->whereNumber('exam_id')->group(function () {
            Route::get('/', 'listQuestions')->name('admin.exams.questions');
            Route::post('/store', 'storeQuestion')->name('admin.exams.questions.store');
            Route::get('/create', 'createQuestion')->name('admin.questions.create');
        });
    });
    // Gestion individuelle des questions (hors contexte d'examen spécifique)
    Route::prefix('admin/questions')->controller(AdminExamsController::class)->group(function () {
        Route::delete('/{id}/delete', 'deleteQuestion')->whereNumber('id')->name('admin.questions.delete');
        Route::get('/{id}/edit', 'editQuestion')->whereNumber('id')->name('admin.questions.edit');
        Route::put('/{id}/update', 'updateQuestion')->whereNumber('id')->name('admin.questions.update');
    });
});

Route::prefix('certificates')->controller(CertificateController::class)->group(function () {
    Route::post('/generate/{exam_user_id}', 'generateCertificate')->whereNumber('exam_user_id')->name('certificates.generate');
    Route::get('/{certificate_url}/scan', 'scanCertificate')->where('certificate_url', '.*')->name('certificates.scan');
    Route::get('/{certificate_url}/view', 'viewCertificate')->where('certificate_url', '.*')->name('certificates.view');
});






