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
use Illuminate\Support\Facades\Crypt;

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

                    // Vérifier si un coupon est appliqué

            $discount = session('discount', 0);
            $couponCode = session('coupon_code', null);
            // ✅ Récupérer les cours du panier
            $cartCount = session('cart', []);
            $courses = Course::whereIn('id', $cartCount)->get();
            $totalPrice = $courses->sum('price');
            $finalPrice = max(0, $totalPrice - $discount);


            // ✅ Vérifier que le total payé correspond bien au total des cours sélectionnés
            if ($validatedData['subtotal'] != $totalPrice) {
                flash()->error('Payment total mismatch. Please refresh the page.');
                return redirect()->back();
            }
            $encryptedUserId = Crypt::encryptString($user->id);
            // ✅ Créer la session de paiement
            $checkoutUrl = $this->paymentService->createCheckoutSession($courses, $finalPrice, $encryptedUserId, $couponCode);
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


}
