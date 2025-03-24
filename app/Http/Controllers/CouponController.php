<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    /**
     * Afficher la liste des coupons
     */
    public function show(Coupon $coupon)
    {
        // Charger les utilisateurs qui ont utilisé ce coupon
        $coupon->load('users');

        return view('adminpanel.coupons.show', compact('coupon'));
    }


    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return view('adminpanel.coupons.index', compact('coupons'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('adminpanel.coupons.create');
    }

    /**
     * Stocker un coupon en base de données
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:255',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'expiration_date' => 'required|date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon créé avec succès !');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Coupon $coupon)
    {
        return view('adminpanel.coupons.edit', compact('coupon'));
    }

    /**
     * Mettre à jour un coupon
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id . '|max:255',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'expiration_date' => 'required|date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon mis à jour avec succès !');
    }

    /**
     * Supprimer un coupon
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon supprimé avec succès !');
    }

    public function applyCoupon(Request $request)
    {
        $couponCode = $request->coupon_code;
        $coupon = Coupon::where('code', $couponCode)->first();
        $user = auth()->user();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon.'
            ]);
        }

        // Récupération du total actuel
        $cartCount = session('cart', []);
        if (empty($cartCount)) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.'
            ]);
        }

        $subtotal = Course::whereIn('id', $cartCount)->sum('price');

        // Calcul du discount
        $discount = $coupon->discount_type === 'fixed' ? $coupon->discount_value : ($subtotal * ($coupon->discount_value / 100));
        $newTotal = max(0, $subtotal - $discount);

        // Stocker en session (AU LIEU DE PASSER EN INPUT CACHÉ)
        session([
            'discount' => $discount,
            'coupon_code' => $coupon->code,
            'applied_coupon_id' => $coupon->id,
            'payable_total' => $newTotal,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Coupon applied! Discount: $" . number_format($discount, 2),
            'discount' => number_format($discount, 2),
            'new_total' => number_format($newTotal, 2),
        ]);
    }


}

