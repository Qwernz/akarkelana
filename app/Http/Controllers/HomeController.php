<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Fungsi harus berada DI DALAM class
    public function index() {
        $products = \App\Models\Product::all();
        return view('welcome', compact('products'));
    }
}