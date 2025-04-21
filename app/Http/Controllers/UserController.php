<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{



    public function profile()
    {
        $user = auth()->user();

        // Récupérer les paiements de l'utilisateur connecté
        $payments = auth()->user()->payments()
        ->with('orders.course') // Charger les commandes et les cours associés
        ->orderBy('created_at', 'desc')
        ->get(['id', 'total_price as amount', 'status', 'created_at as date']);


        $payments->transform(function ($payment) {
            $payment->date = \Carbon\Carbon::parse($payment->date);
            return $payment;
        });

        return view('user', compact('payments','user'));
    }
}
