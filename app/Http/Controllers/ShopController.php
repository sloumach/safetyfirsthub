<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;



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
        /* dd($cartCount); */
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

    public function addToWishlist(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $userId = auth()->id();
        $courseId = $request->input('course_id');

        try {
            // Check if already in wishlist
            $exists = Wishlist::where('user_id', $userId)
                ->where('product_id', $courseId)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course already in wishlist'
                ], 409);
            }

            // Add to wishlist
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $courseId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course added to wishlist successfully!'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding course to wishlist'
            ], 500);
        }
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id', // Vérifie que le cours existe
        ]);

        $user = auth()->user();
        $courseId = $request->input('course_id');

        // Vérifier si l'utilisateur a déjà acheté le cours
        if ($user && $user->courses->contains($courseId)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already purchased this course.',
            ], 400); // Code 400 pour indiquer une erreur côté client
        }

        // Gestion du panier en session
        $cart = Session::get('cart', []);

        if (in_array($courseId, $cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in your cart!',
                'cart_count' => count($cart), // Retourner le nombre d'éléments dans le panier
            ], 400);
        }

        // Ajouter le cours au panier
        session()->push('cart', $courseId);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => count(session('cart', [])), // Retourne le nouveau nombre d'éléments
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id', // Vérifie que le cours existe
        ]);

        $courseId = $request->input('course_id');
        $cart = Session::get('cart', []);

        // Vérifier si le cours est bien dans le panier
        if (!in_array($courseId, $cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found in cart.',
                'cart_count' => count($cart),
            ], 400);
        }

        // Supprimer le cours du panier
        $cart = array_filter($cart, fn($id) => $id != $courseId);
        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Course removed from cart successfully!',
            'cart_count' => count($cart),
        ]);
    }

    public function removeFromWishlist(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $userId = auth()->id();
        $courseId = $request->input('course_id');

        try {
            Wishlist::where('user_id', $userId)
                ->where('product_id', $courseId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Course removed from wishlist successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing course from wishlist'
            ], 500);
        }
    }

}
