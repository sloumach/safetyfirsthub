<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Models\Course;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\CoursePurchased;
use Flasher\Prime\FlasherInterface;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function checkout()
    {
        try {
            $cartCount = session('cart', []);
            if (empty($cartCount)) {
                flash()->error('Your cart is empty.');
                return redirect()->back();
            }

            $courses = Course::whereIn('id', $cartCount)->get();
            $subtotal = $courses->sum('price');

            return view('checkout', compact('subtotal'));
        } catch (\Exception $e) {
            Log::error("Error in checkout: " . $e->getMessage());
            flash()->error('An error occurred during checkout.');
            return redirect()->back();
        }
    }

    public function charge(Request $request)
    {
        try {
            // Validation with flash messages for each error
            $rules = [
                'country'        => 'required|string|max:255',
                'street_address' => 'required|string|max:255',
                'city'          => 'required|string|max:255',
                'state'         => 'required|string|max:255',
                'zip'           => 'required|string|max:20',
                'subtotal'      => 'required|numeric|min:0',
            ];

            try {
                $validatedData = $request->validate($rules);
            } catch (\Illuminate\Validation\ValidationException $e) {
                foreach ($e->errors() as $field => $messages) {
                    foreach ($messages as $message) {
                        flash()->error($field . ': ' . $message);
                    }
                }
                return back()->withInput();
            }

            $user = auth()->user();

            // ✅ Mettre à jour les informations de facturation de l'utilisateur
            $user->update([
                'country' => $validatedData['country'],
                'adress'  => $validatedData['street_address'], // 🟢 Corrigé de "adress" en "address"
                'city'    => $validatedData['city'],
                'state'   => $validatedData['state'],
                'zipcode' => $validatedData['zip'],
            ]);

            // ✅ Récupérer les cours du panier
            $cartCount = session('cart', []);
            $courses = Course::whereIn('id', $cartCount)->get();
            $totalPrice = $courses->sum('price');

            // ✅ Vérifier que le total payé correspond bien au total des cours sélectionnés
            if ($validatedData['subtotal'] != $totalPrice) {
                flash()->error('Payment total mismatch. Please refresh the page.');
                return redirect()->back();
            }

            // ✅ Créer la session de paiement
            $checkoutUrl = $this->paymentService->createCheckoutSession($courses, $totalPrice);
            if (!$checkoutUrl) {
                flash()->error('Failed to initiate payment.');
                return redirect()->back();
            }

            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            Log::error("Error in charge: " . $e->getMessage());
            flash()->error('An error occurred while processing the payment.');
            return redirect()->back();
        }
    }


    public function syncPayment(Request $request)
    {
        try {
            $sessionId = $request->query('session_id');
            $paymentStatus = $this->paymentService->processPayment($sessionId);

            if ($paymentStatus === 'failed') {
                flash()->error('Payment failed.');
                return redirect()->route('checkout.cancel');
            }

            // Assigner le rôle "student" si ce n'est pas déjà fait
            $user = Auth::user();
            $studentRole = Role::where('name', 'student')->first();
            if (!$studentRole || !$user->roles()->where('name', 'student')->exists()) {
                $user->roles()->sync([$studentRole->id]);
            }

            flash()->success('Payment successful.');
            return redirect()->route('checkout.success');
        } catch (\Exception $e) {
            Log::error("Error in syncPayment: " . $e->getMessage());
            flash()->error('An error occurred while syncing the payment.');
            return redirect()->route('checkout.cancel');
        }
    }

    public function successPage()
    {
        return view('success');
    }

    public function cancelPage()
    {
        return view('failed');
    }



    /* public function pay (){

        $cartCount = session('cart', []);
        $courses = Course::whereIn('id', $cartCount)->get();
        $totalPrice = $courses->sum('price');


        return view ('payment',compact('totalPrice'));
    } */
    /* public function syncPayment2(Request $request)
    {
        Stripe::setApiKey(env('stripe_secret_key'));

        if (!$request->query('session_id')) {
            return redirect()->route('checkout.cancel');
        }

        $session = Session::retrieve($request->query('session_id'));

        if (!$session || $session->payment_status !== 'paid') {
            return redirect()->route('checkout.cancel')->with('error', 'Paiement non confirmé.');
        }

        if (session()->has('processed_payments') && in_array($request->query('session_id'), session('processed_payments'))) {
            return redirect()->route('checkout.cancel')->with('error', 'Paiement non confirmé.');
        }

        // Ajouter à la session pour éviter de traiter le paiement plusieurs fois
        session()->push('processed_payments', $request->query('session_id'));
        $cartCount = session('cart', []);
        $courses = Course::whereIn('id', $cartCount)->get();

        $user = Auth::user();

        // Ajouter le rôle "student"
        $studentRole = Role::where('name', 'student')->first();
        $user->roles()->syncWithPivotValues([$studentRole->id], [
            'updated_at' => now(),
        ]);

        // Associer les cours
        $cartCount = session('cart', []);
        $courses = Course::whereIn('id', $cartCount)->get();

        foreach ($courses as $course) {
            $user->courses()->attach($course->id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Vider le panier
        session()->forget('cart');
        return view('success'); //ici retourner à une route pas un view et vérifier en haut de ecette methode //a faire le blade success

    } */
    /* public function charge(Request $request)
    {
        $cartCount = session('cart', []); // Récupérer les IDs des cours dans le panier
        $courses = Course::whereIn('id', $cartCount)->get(); // Obtenir les cours
        $totalPrice = $courses->sum('price'); // Calculer le total

        try {
            // Initialiser le client Stripe
            $stripe = new StripeClient(env('stripe_secret_key'));

            // Créer un PaymentIntent
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $totalPrice * 100, // Montant en cents
                'currency' => 'usd',
                'payment_method_types' => ['card'], // Type de paiement
                'description' => 'Payment for ' . implode(', ', $courses->pluck('category')->toArray()), // Description
            ]);


            // Si le paiement est validé (via webhook ou confirmation frontend), ajuster le rôle et associer les cours
            if ($paymentIntent->status === 'succeeded') {
                $user = Auth::user();

                // Ajouter le rôle "student" si l'utilisateur ne l'a pas encore
                $studentRole = Role::where('name', 'student')->first();

                $user->roles()->sync([$studentRole->id]);

                // Associer les cours achetés à l'utilisateur
                foreach ($courses as $course) {
                    $user->courses()->attach($course->id);
                }

                // Vider le panier après un achat réussi
                session()->forget('cart');

                // Retourner une réponse de succès
                return response()->json(['success' => true, 'message' => 'Payment succeeded!']);
            }

            // Retourner le client_secret pour gérer les actions frontend (3D Secure, etc.)
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            // Gérer les erreurs
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function syncPayment(Request $request)
    {
        try {
            $paymentIntentId = $request->paymentIntentId;

            $stripe = new StripeClient(env('stripe_secret_key'));
            $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                $user = Auth::user();

                // Ajouter le rôle "student"
                $studentRole = Role::where('name', 'student')->first();
                $user->roles()->syncWithPivotValues([$studentRole->id], [
                    'updated_at' => now(),
                ]);

                // Associer les cours
                $cartCount = session('cart', []);
                $courses = Course::whereIn('id', $cartCount)->get();

                foreach ($courses as $course) {
                    $user->courses()->attach($course->id, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Vider le panier
                session()->forget('cart');

                return response()->json(['success' => true]);
            }

            return response()->json(['error' => 'Payment not succeeded'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    } */


}
