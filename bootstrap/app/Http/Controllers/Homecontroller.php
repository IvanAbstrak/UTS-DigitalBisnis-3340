<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Partner;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil data kategori dan partner dari database
        $categories = Category::all();
        $partners = Partner::all();

        // Kirim data ke view 'welcome'
        return view('welcome', compact('categories', 'partners'));
    }
}
