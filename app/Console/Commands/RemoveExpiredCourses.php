<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\Certificate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Events\CourseExpiringInOneMonth;
use App\Events\CourseExpiringInFifteenDays;

class RemoveExpiredCourses extends Command
{
    protected $signature = 'courses:remove-expired';
    protected $description = 'Supprime les cours expirés pour les utilisateurs';

    public function handle()
    {
        $users = User::with(['courses' => function ($query) {
            $query->withPivot('created_at');
        }, 'roles'])->get();

        $expiredCourses = [];
        $expiredCertificates = [];
        $usersToUpdateRole = [];

        foreach ($users as $user) {
            $activeCourses = 0;
            $coursesToExpire = [];

            foreach ($user->courses as $course) {
                $purchaseDate = $course->pivot->created_at;
                $expirationDate = Carbon::parse($purchaseDate)->addMonths(3);
                $daysRemaining = now()->diffInDays($expirationDate, false);

                if ($daysRemaining === 30 || $daysRemaining === 15) {
                    $hasPassedExam = $user->examUsers()
                        ->whereHas('exam', fn($q) => $q->where('course_id', $course->id))
                        ->exists();

                    $hasNotPassedExam = !$hasPassedExam;

                    $mailClass = $daysRemaining === 30
                        ? event(new CourseExpiringInOneMonth($user, $course, 30, $hasNotPassedExam))
                        : event (new CourseExpiringInFifteenDays($user, $course, 15, $hasNotPassedExam));

                }


                if (now()->greaterThan($expirationDate)) {
                    // Stocker les cours expirés
                    $coursesToExpire[] = $course->id;
                    $expiredCertificates[] = $course->id;
                } else {
                    $activeCourses++; // Il reste au moins un cours actif
                }
            }

            if (!empty($coursesToExpire)) {
                $expiredCourses[$user->id] = $coursesToExpire;
            }

            // Vérifier si l'utilisateur doit perdre le rôle "student"
            if ($activeCourses === 0 && $user->roles->contains('name', 'student')) {
                $usersToUpdateRole[] = $user->id;
            }
        }

        // 🔹 Détacher **uniquement** les cours expirés pour chaque utilisateur
        foreach ($expiredCourses as $userId => $courses) {
            User::find($userId)->courses()->detach($courses);
        }

        // 🔹 Désactiver uniquement les certificats des cours expirés
        Certificate::whereHas('examUser.exam', function ($query) use ($expiredCertificates) {
            $query->whereIn('course_id', $expiredCertificates);
        })->update(['available' => false]);

        // 🔹 Mise à jour des rôles des utilisateurs (Retirer "student", Ajouter "user")
        if (!empty($usersToUpdateRole)) {
            $studentRole = Role::where('name', 'student')->first();
            $userRole = Role::where('name', 'user')->first();

            foreach ($usersToUpdateRole as $userId) {
                $user = User::find($userId);
                $user->roles()->detach($studentRole->id); // Retirer "student"
                $user->roles()->syncWithoutDetaching([$userRole->id]); // Ajouter "user"
            }
        }

        $this->info('✅ Vérification des cours expirés terminée.');
        Log::info('✅ Vérification des cours expirés et gestion des rôles terminée.');

    }
}
