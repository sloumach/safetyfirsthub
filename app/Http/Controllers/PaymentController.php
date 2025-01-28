<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Course;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function checkout(){
        $cartCount = session('cart', []);
        if (empty($cartCount)) {
            //à afficher une notification
            return redirect()->back();
        }

        $courses = Course::whereIn('id', $cartCount)->get();
        $subtotal = $courses->sum('price');

        return view('checkout',compact('subtotal'));
    }
    public function payment(Request $request){

        //dd($request->subtotal);
        /* $validatedData = $request->validate([
            'first_name' => 'required|in:' . auth()->user()->first_name, // Empêche les modifications
            'last_name' => 'required|in:' . auth()->user()->last_name, // Empêche les modifications
            'country' => 'required|integer',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'subtotal' => 'required|numeric|min:0', // Vérifie que le subtotal est envoyé
        ]); */
        // Récupération de l'utilisateur connecté
        $user = auth()->user();

        // Mise à jour des informations utilisateur
        /* $user->update([
            'country_id' => $validatedData['country'],
            'street_address' => $validatedData['street_address'],
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
            'zip' => $validatedData['zip'],
        ]); */

        // Redirection ou retour de la réponse
        return redirect()->route('pay')/* ->with('total', $validatedData['subtotal']) */;

        dd('ss');
    }
    public function pay (){

        $cartCount = session('cart', []);
        $courses = Course::whereIn('id', $cartCount)->get();
        $totalPrice = $courses->sum('price');

        //dd($totalPrice);
        return view ('payment',compact('totalPrice'));
    }
    public function charge(Request $request)
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
            /* dd($paymentIntent->status);

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
            } */

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
    }


}
