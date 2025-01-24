<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index () {




        return view('adminpanel.index');
    }
    public function courses () {




        return view('adminpanel.courses');
    }

    public function addcourse (Request $request) {

        // Valider les données
        $validatedData = $request->validate([
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'total_videos' => 'required|integer|min:1',
            'description' => 'required|string|max:1000',
            'cover' => 'required|file|mimes:jpg,jpeg,png|max:2048', // Max 2MB pour l'image
        ]);


        // Gérer l'image de couverture
        $coverPath = $request->file('cover')->store('courses/covers', 'public');

        // Enregistrer les données dans la base
        Course::create([
            'price' => $validatedData['price'],
            'category' => $validatedData['category'],
            'total_videos' => $validatedData['total_videos'],
            'description' => $validatedData['description'],
            'cover' => $coverPath, // Chemin de la couverture
        ]);

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Course added successfully!');


        return view('adminpanel.courses');
    }

}
