<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Course;
use App\Models\Certificate;
use Carbon\Carbon;

class RemoveExpiredCourses extends Command
{
    protected $signature = 'courses:remove-expired';
    protected $description = 'Supprime les cours expirés pour les utilisateurs';

    public function handle()
    {
        $users = User::with('courses')->get();

        foreach ($users as $user) {
            foreach ($user->courses as $course) {
                $purchaseDate = $user->courses()->where('course_id', $course->id)->first()->pivot->created_at;
                $expirationDate = Carbon::parse($purchaseDate)->addMonths($course->duration);

                if (now()->greaterThan($expirationDate)) {
                    // Détacher le cours de l'utilisateur
                    $user->courses()->detach($course->id);

                    // Désactiver le certificat lié à ce cours
                    Certificate::where('user_id', $user->id)
                        ->whereHas('examUser.exam', function ($query) use ($course) {
                            $query->where('course_id', $course->id);
                        })
                        ->update(['available' => false]);

                    $this->info("Cours expiré retiré pour l'utilisateur: {$user->id}, Cours: {$course->id}");
                }
            }
        }

        $this->info('Vérification des cours expirés terminée.');
    }
}
