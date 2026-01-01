<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        
        $categories = ProductCategory::where('is_active', true)
            ->orderBy('name')
            ->limit(12)
            ->get(['id', 'name']);

        $products = Product::with(['images', 'category'])
            ->where('status', 'active') 
            ->where('is_visible', true)    
            ->when($request->search, function($q) use ($request) {
                $searchLower = strtolower(trim($request->search));
                $q->where(function($query) use ($searchLower) {
                    // 🔥 AMAZON FUZZY SEARCH - Multiple fields
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"])
                          ->orWhereRaw('LOWER(brand) LIKE ?', ["%{$searchLower}%"])
                          ->orWhereRaw('LOWER(short_description) LIKE ?', ["%{$searchLower}%"])
                          ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchLower}%"])
                          // Category name search
                          ->orWhereHas('category', function($cat) use ($searchLower) {
                              $cat->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"]);
                          });
                });
            })
            ->when($request->category, function($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            ->orderByRaw($request->get('sort', 'created_at') === 'price-low' ? 
                'COALESCE(sale_price, price) ASC' : 
                ($request->get('sort') === 'price-high' ? 
                    'COALESCE(sale_price, price) DESC' : 'created_at DESC')
            )
            ->paginate(24);

        return view('home', compact('products', 'categories'));
    }
}
