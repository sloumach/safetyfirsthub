<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Flasher\Toastr\Prime\ToastrFactory;


class ShopController extends Controller
{
    //


    public function index () {

        $products = Course::all();


        return view('shop',compact('products'));
    }

    public function cart () {
        // Récupère les IDs depuis la session
        $cartCount = session('cart', []); // Par défaut, retourne un tableau vide si 'cart' est inexistant

        // Vérifie si le tableau n'est pas vide
        if (empty($cartCount)) {
            $courses = collect(); // Crée une collection vide
            return view('cart', compact('courses'));
        }
        // Récupère les cours correspondant aux IDs
        $courses = Course::whereIn('id', $cartCount)->get();



        return view('cart',compact('courses'));
    }

    public function wishlist () {

        $user = auth()->user();

        // Charge les produits de la wishlist de cet utilisateur
        $wishlistedCourses = $user->wishlists()->with('course')->get();

        //dd($wishlistedCourses);

        return view('wishlist',compact('wishlistedCourses'));
    }

    public function addToCart(Request $request, ToastrFactory $flasher){

        $request->validate([
            'product_id' => 'required|exists:courses,id', // Vérifie que le produit existe
        ]);
        $user = auth()->user();

        // Récupérer l'ID du produit depuis la requête
        $productId = $request->input('product_id');
        if ($user && $user->courses->contains($productId)) {
            $flasher->addWarning('You have already purchased this course.');
            return response()->json([
                'success' => false,
                'message' => 'You have already purchased this course.',
                'flasher_html' => $flasher->render(), // Générer le HTML de la notification

            ], 400); // Code 400 pour indiquer une erreur côté client
        }

        // Ajouter l'ID du produit à la session
        $cart = Session::get('cart', []);
        // Vérifier si l'ID n'est pas déjà présent dans le panier
        if (!in_array($productId, $cart)) {
            $cart[] = $productId; // Ajouter l'ID au panier
            Session::put('cart', $cart); // Enregistrer dans la session
        }
        $flasher->addSuccess('Product added to cart successfully!');

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'flasher_html' => $flasher->render(),
        ]);
    }

    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id', // Vérifie que le produit existe
        ]);

        $userId = auth()->id(); // Récupère l'utilisateur connecté
        $productId = $request->input('product_id');

        // Vérifie si le produit est déjà dans la wishlist
        $exists = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Product already in wishlist'], 409);
        }

        // Ajoute le produit à la wishlist
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return response()->json(['message' => 'Product added to wishlist'], 201);
    }
}
