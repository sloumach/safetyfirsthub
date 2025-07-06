<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\ExamRepositoryInterface;
use App\Repositories\ExamRepository;
use App\Repositories\Interfaces\QuestionRepositoryInterface;
use App\Repositories\QuestionRepository;
use App\Repositories\Interfaces\QuizQuestionRepositoryInterface;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use App\Repositories\QuizQuestionRepository;
use App\Repositories\QuizRepository;
use App\Repositories\Interfaces\CertificateRepositoryInterface;
use App\Repositories\CertificateRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(QuizRepositoryInterface::class, QuizRepository::class);
        $this->app->bind(QuizQuestionRepositoryInterface::class, QuizQuestionRepository::class);
        $this->app->bind(CertificateRepositoryInterface::class, CertificateRepository::class);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partager la variable dans toutes les vues
        View::composer('navbar', function ($view) {
            $cartCount = session('cart') ? count(session('cart')) : 0;
            $view->with('cartCount', $cartCount);
        });
    }
}
