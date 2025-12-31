<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class ProductController extends Controller
{
    public function index(){

        $stats = [
        'total_products' => Product::count(),
        'total_active_products' => Product::where('status','active')->count(),
        'total_inactive_products' => Product::where('status','inactive')->count(),
        
        ];

    }

   
    
}
