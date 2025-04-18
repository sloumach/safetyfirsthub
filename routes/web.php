<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserExamsController;
use App\Http\Controllers\AdminExamsController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ExamAttemptController;
use App\Http\Controllers\VideoProgressController;
use App\Http\Controllers\AdminQuizController;
use App\Http\Controllers\AdminQuizQuestionController;
use App\Http\Controllers\SectionQuizController;





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

Route::group([ 'middleware' => ['auth'/* ,'verified' */]], function () {

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

Route::group(['middleware' => ['auth', /* 'verified', */ 'role:student']], function () {
    // Dashboard route
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard/{any?}', 'index')->where('any', '.*')->name('dashboard');
        // API routes under '/api' prefix
        Route::prefix('api')->group(function () {
            Route::get('/courses', 'getCourses')->name('api.courses');
            Route::get('/course/{id}', 'getCourse')->whereNumber('id')->name('api.course');
            // 📌 Route pour récupérer l'URL temporaire de la vidéo
            Route::get('/videos/{video_id}/video-url',  'getVideoUrl')->name('api.section.video.url');

            // 📌 Route pour streamer la vidéo sécurisée avec une URL signée
            Route::get('/videos/{video_id}/video-stream',  'streamVideo')->middleware('signed')->name('section.video.stream');

        });
        // Secure storage access route
        Route::get('/storage/private-cover/{filename}', 'serveCover')->name('cover.access');

    });
    Route::prefix('exam')->group(function () {
        Route::post('/start/{course_id}', [ExamController::class, 'startExam'])->whereNumber('course_id');
        Route::get('/{session_id}/result', [ExamController::class, 'examResults'])->whereNumber('session_id');
        Route::post('/{session_id}/complete', [ExamController::class, 'markExamAsCompleted'])->whereNumber('session_id');
        Route::get('/history', [ExamController::class, 'userExamHistory']);
    });
    // 📌 Routes pour la gestion des réponses et des questions
    Route::prefix('exam/{session_id}')->group(function () {
        Route::get('/next-question', [ExamAttemptController::class, 'getNextQuestion'])->whereNumber('session_id')->name('exam.next_question');
        Route::post('/submit-answer', [ExamAttemptController::class, 'submitAnswer'])->whereNumber('session_id');
    });
    // 📌 Routes pour la gestion de la progression vidéo
    Route::prefix('video/progress')->group(function () {
        Route::post('/update', [VideoProgressController::class, 'updateProgress']);
        Route::get('/check/{course_id}', [VideoProgressController::class, 'checkProgress'])->whereNumber('course_id');
        Route::post('/complete', [VideoProgressController::class, 'markAsCompleted']);
        Route::get('/course/{course_id}', [VideoProgressController::class, 'checkCourseCompletion'])
        ->whereNumber('course_id');

    });
    Route::get('/courses/{course_id}/sections', [DashboardController::class, 'getCourseSections'])
    ->whereNumber('course_id');
    Route::prefix('certificates')->controller(CertificateController::class)->group(function () {
        Route::get('/generate/{exam_id}', 'generateCertificate')->whereNumber('exam_id')->name('certificates.generate');
        Route::get('/{certificate_url}/scan', 'scanCertificate')->where('certificate_url', '.*')->name('certificates.scan');
        Route::get('/{certificate_url}/view', 'viewCertificate')->where('certificate_url', '.*')->name('certificates.view');
    });
    Route::controller(SectionQuizController::class)->group(function () {
        Route::post('/sections/{sectionId}/quiz/submit', [SectionQuizController::class, 'submitQuizAnswers']);
    });


});
    // ------------------ Routes pour l'administration ------------------
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

    Route::get('/admin/course/edit/{id}', 'edit')->name('edit.course');


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


    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('coupons', CouponController::class)->except(['show']);
        Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon');
        Route::get('/coupons/{coupon}', [CouponController::class, 'show'])->name('coupons.show');


        Route::resource('quizzes', AdminQuizController::class);
        Route::get('quizzes/{quiz_id}/questions', [AdminQuizQuestionController::class, 'index'])->name('questions.index');
        Route::resource('quizzes.questions', AdminQuizQuestionController::class)->except(['index']);


    });
});








