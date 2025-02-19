<?php

namespace App\Services;

use Stripe\Stripe;
use App\Models\Order;
use App\Models\Course;
use App\Models\Payment;
use Stripe\Checkout\Session;
use App\Events\CoursePurchased;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    public function createCheckoutSession($courses, $totalPrice)
    {
        try {
            Stripe::setApiKey(env('stripe_secret_key'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $totalPrice * 100,
                        'product_data' => [
                            'name' => 'Purchase Courses',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('syncPayment') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
            ]);

            return $session->url;
        } catch (\Exception $e) {
            Log::error("Error in createCheckoutSession: " . $e->getMessage());
            return null;
        }
    }

    public function processPayment($sessionId)
    {
        try {
            Stripe::setApiKey(env('stripe_secret_key'));

            if (!$sessionId) {
                throw new \Exception("Invalid payment session.");
            }

            $session = Session::retrieve($sessionId);
            if (!$session) {
                throw new \Exception("Failed to retrieve payment session.");
            }

            $user = Auth::user();
            $cartCourses = session('cart', []);
            $courses = Course::query()->whereIn('id', $cartCourses)->get();
            $totalPrice = $courses->sum('price');

            $paymentStatus = ($session->payment_status === 'paid') ? 'completed' : 'failed';

            // Enregistrement du paiement
            $payment = Payment::create([
                'user_id' => $user->id,
                'payment_id' => $session->id,
                'total_price' => $totalPrice,
                'status' => $paymentStatus,
            ]);

            // Enregistrement des commandes
            foreach ($courses as $course) {
                // Créer l'enregistrement de la commande
                Order::create([
                    'payment_id' => $payment->id,
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ]);

                // Associer l'utilisateur au cours acheté
                $user->courses()->attach($course->id, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 🔹 Déclencher l'événement pour CHAQUE cours acheté
                event(new CoursePurchased($user, $course));
            }

            // Nettoyer le panier après un paiement réussi
            session()->forget('cart');

            return $paymentStatus;
        } catch (\Exception $e) {
            Log::error("Error in processPayment: " . $e->getMessage());
            return 'failed';
        }
    }
}
