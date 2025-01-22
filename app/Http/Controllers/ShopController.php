<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    //


    public function index () {



        return view('shop');
    }

    public function cart () {



        return view('cart');
    }

    public function wishlist () {



        return view('wishlist');
    }

    public function addToCart(Request $request){

        // Récupérer l'ID du produit depuis la requête
        $productId = $request->input('product_id');

        // Ajouter l'ID du produit à la session
        $cart = Session::get('cart', []);
        // Vérifier si l'ID n'est pas déjà présent dans le panier
        if (!in_array($productId, $cart)) {
            $cart[] = $productId; // Ajouter l'ID au panier
            Session::put('cart', $cart); // Enregistrer dans la session
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
        ]);
    }
}
