<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Jobs\SendEmailVerificationJob;
use Illuminate\Auth\Events\Registered;
use Flasher\Prime\FlasherInterface;

class RegisteredUserController extends Controller
{
    protected $flasher;

    public function __construct(FlasherInterface $flasher)
    {
        $this->flasher = $flasher;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('registration');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'firstname' => ['required', 'string', 'max:25'],
                'lastname' => ['required', 'string', 'max:25'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:50', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $userRole = Role::where('name', 'user')->first();
            $user->roles()->attach($userRole, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //SendEmailVerificationJob::dispatch($user);
            event(new Registered($user));

            Auth::login($user);

            $this->flasher->addSuccess('Registration successful! Welcome to SafetyFirstHub.');
            return redirect(route('shop', absolute: false));

        } catch (\Illuminate\Validation\ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->flasher->addError($field . ': ' . $message);
                }
            }
            return back()->withInput();
        } catch (\Exception $e) {
            $this->flasher->addError('An error occurred during registration. Please try again.');
            return back()->withInput();
        }
    }
}
