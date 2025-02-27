<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Models\Course;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\CoursePurchased;

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
                return redirect()->back()->with('error', 'Your cart is empty.');
            }

            $courses = Course::whereIn('id', $cartCount)->get();
            $subtotal = $courses->sum('price');

            return view('checkout', compact('subtotal'));
        } catch (\Exception $e) {
            Log::error("Error in checkout: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during checkout.');
        }
    }

    public function charge(Request $request)
    {
        try {
            // ✅ Validation des données du formulaire
            $validatedData = $request->validate([
                'country'        => 'required|string|max:255',
                'street_address' => 'required|string|max:255',
                'city'           => 'required|string|max:255',
                'state'          => 'required|string|max:255',
                'zip'            => 'required|string|max:20',
                'subtotal'       => 'required|numeric|min:0',
            ]);

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
                return redirect()->back()->with('error', 'Payment total mismatch. Please refresh the page.');
            }

            // ✅ Créer la session de paiement
            $checkoutUrl = $this->paymentService->createCheckoutSession($courses, $totalPrice);
            if (!$checkoutUrl) {
                return redirect()->back()->with('error', 'Failed to initiate payment.');
            }

            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            Log::error("Error in charge: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while processing the payment.');
        }
    }


    public function syncPayment(Request $request)
    {
        try {
            $sessionId = $request->query('session_id');
            $paymentStatus = $this->paymentService->processPayment($sessionId);

            if ($paymentStatus === 'failed') {
                return redirect()->route('checkout.cancel')->with('error', 'Payment failed.');
            }

            // Assigner le rôle "student" si ce n'est pas déjà fait
            $user = Auth::user();
            $studentRole = Role::where('name', 'student')->first();
            if (!$studentRole || !$user->roles()->where('name', 'student')->exists()) {
                $user->roles()->sync([$studentRole->id]);
            }

            return redirect()->route('checkout.success')->with('success', 'Payment successful.');
        } catch (\Exception $e) {
            Log::error("Error in syncPayment: " . $e->getMessage());
            return redirect()->route('checkout.cancel')->with('error', 'An error occurred while syncing the payment.');
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


}
