<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

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

        dd($request->subtotal);
        $validatedData = $request->validate([
            'first_name' => 'required|in:' . auth()->user()->first_name, // Empêche les modifications
            'last_name' => 'required|in:' . auth()->user()->last_name, // Empêche les modifications
            'country' => 'required|integer',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'subtotal' => 'required|numeric|min:0', // Vérifie que le subtotal est envoyé
        ]);
        // Récupération de l'utilisateur connecté
        $user = auth()->user();

        // Mise à jour des informations utilisateur
        $user->update([
            'country_id' => $validatedData['country'],
            'street_address' => $validatedData['street_address'],
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
            'zip' => $validatedData['zip'],
        ]);

        // Redirection ou retour de la réponse
        return redirect()->route('pay')->with('total', $validatedData['subtotal']);

        dd('ss');
    }
}
