<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    /**
     * Afficher la liste des coupons
     */
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
}

