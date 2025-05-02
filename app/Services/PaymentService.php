<?php

namespace App\Services;

use Stripe\Stripe;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Payment;
use Stripe\Checkout\Session;
use App\Events\CoursePurchased;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PaymentService
{
    public function createCheckoutSession($courses, $totalPrice,$encryptedUserId, $couponCode)
    {
        try {
            Stripe::setApiKey(env('stripe_secret_key'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'locale' => 'auto',
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
                    'total_price' => $totalPrice,
                    'coupon_code' => $couponCode,
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
            $totalPrice = $session->metadata->total_price ; // Stripe retourne en centimes, donc division par 100
            $couponCode = $session->metadata->coupon_code;
            $userId  = Crypt::decryptString($encryptedUserId);
            $user = User::find($userId);
            $courseIds = explode(',', $session->metadata->course_ids);
            $courseIds = explode(',', $session->metadata->course_ids);
            $courses = Course::query()->whereIn('id', $courseIds)->get();

            $calculatedTotal = $courses->sum('price');
            /* if ($calculatedTotal != $totalPrice) {
                throw new \Exception("Payment amount mismatch. Expected: $calculatedTotal, Received: $totalPrice");
            } */

            $paymentStatus = ($session->payment_status === 'paid') ? 'completed' : 'failed';
            Log::error("payment status:paymentStatus === completed ". $paymentStatus);
            // Enregistrement du paiement
            $payment = Payment::create([
                'user_id' => $userId,
                'payment_id' => $session->id,
                'total_price' => $totalPrice,
                'status' => $paymentStatus,
            ]);
            Log::error("payment status: ".$paymentStatus);
            Log::error("payment status: ".$totalPrice);
            if ($paymentStatus === 'completed') {
                foreach ($courses as $course) {
                    Log::error("payment status:paymentStatus === completed ");
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
                if ($couponCode) {
                    DB::beginTransaction();
                    try {
                        $coupon = Coupon::where('code', $couponCode)->lockForUpdate()->first();

                        if (!$coupon || !$coupon->isValid()) {
                            throw new \Exception("Coupon is no longer valid.");
                        }

                        $existingUsage = $coupon->users()->where('user_id', $user->id)->first();
                        if ($existingUsage) {
                            $coupon->users()->updateExistingPivot($user->id, [
                                'times_used' => $existingUsage->pivot->times_used + 1
                            ]);

                        } else {
                            $coupon->users()->attach($user->id, ['times_used' => 1]);
                        }
                        $coupon->increment('used_count');
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::error("Error in coupon processing: " . $e->getMessage());
                        DB::rollBack();
                        throw $e; // Important de relancer l'erreur pour être attrapée par le catch principal de processPayment
                    }
                }


                // Nettoyer le panier après un paiement réussi
                // Suppression du coupon après paiement réussi
                session()->forget(['discount', 'coupon_code', 'applied_coupon_id', 'payable_total']);
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
