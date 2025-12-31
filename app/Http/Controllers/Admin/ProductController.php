<?php
// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class ProductController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_active_products' => Product::where('status', 'active')->count(),
            'total_inactive_products' => Product::where('status', 'inactive')->count(),
            'low_stock' => Product::whereColumn('stock_quantity', '<', 'stock_alert')->count(),
            'out_of_stock' => Product::where('stock_quantity', 0)->count(),
        ];

        $sellers = User::where('type', 'seller')
            ->where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('status', 'active');
            }])
            ->select('id', 'name', 'email', 'created_at', 'is_active')
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('stats', 'sellers'));
    }

    public function sellerProducts($id)
    {
        $seller = User::where('id', $id)
                     ->where('type', 'seller')
                     ->firstOrFail();

        $products = Product::where('seller_id', $id)  // ✅ Uses your seller_id
                          ->with(['category', 'images', 'primaryImage', 'seller'])
                          ->latest()
                          ->paginate(20);

        $stats = [
            'total_products' => $products->total(),
            'active_products' => Product::where('seller_id', $id)->where('status', 'active')->count(),
        ];

        return view('admin.sellers.products', compact('seller', 'products', 'stats'));
    }
}
