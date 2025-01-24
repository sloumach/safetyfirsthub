<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function singleproduct($id){

        $product = Course::findOrFail($id);
        return view('single-product',compact('product'));
    }
}
