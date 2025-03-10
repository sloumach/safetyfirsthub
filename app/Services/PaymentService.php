<?php

namespace App\Services;

use Stripe\Stripe;
use App\Models\Order;
use App\Models\Course;
use App\Models\User;
use App\Models\Payment;
use Stripe\Checkout\Session;
use App\Events\CoursePurchased;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PaymentService
{
    public function createCheckoutSession($courses, $totalPrice,$encryptedUserId)
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
                'metadata' => [
                    'course_ids' => implode(',', $courses->pluck('id')->toArray()), // Stocker les IDs des cours
                    'user_id' => $encryptedUserId,
                ],
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

            
            $encryptedUserId = $session->metadata->user_id;
            $userId  = Crypt::decryptString($encryptedUserId);
            $user = User::find($userId);
            $courseIds = explode(',', $session->metadata->course_ids);
            $courses = Course::query()->whereIn('id', $courseIds)->get();
            $totalPrice = $session->amount_total / 100; // Stripe retourne en centimes, donc division par 100
            $calculatedTotal = $courses->sum('price');
            if ($calculatedTotal != $totalPrice) {
                throw new \Exception("Payment amount mismatch. Expected: $calculatedTotal, Received: $totalPrice");
            }



            $paymentStatus = ($session->payment_status === 'paid') ? 'completed' : 'failed';

            // Enregistrement du paiement
            $payment = Payment::create([
                'user_id' => $userId,
                'payment_id' => $session->id,
                'total_price' => $totalPrice,
                'status' => $paymentStatus,
            ]);
            Log::error("payment status: ".$paymentStatus);
            if ($paymentStatus === 'completed') {
                foreach ($courses as $course) {
                    Order::create([
                        'payment_id' => $payment->id,
                        'user_id' => $userId,
                        'course_id' => $course->id,
                    ]);

                    $user->courses()->attach($course->id, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    Log::error("saved order!");
                    event(new CoursePurchased($user, $course));
                }

                // Nettoyer le panier après un paiement réussi
                session()->forget('cart');
            } else {
                throw new \Exception("Payment failed. Courses will not be assigned.");
            }
            Log::error("payment done " );

            return $paymentStatus;
        } catch (\Exception $e) {
            Log::error("Error in processPayment: " . $e->getMessage());
            return 'failed';
        }
    }
}
