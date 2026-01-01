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
            
           

            ->withCount([
                'products as products_count',
                'products as products_count_active' => function($q) {
                    $q->where('status', 'active');
                }
            ])
            ->latest()
            ->groupBy('id')
            ->paginate(10);

   

        return view('admin.products.index', compact('stats', 'sellers'));
    }

    public function sellerProducts($id)
    {
        $seller = User::where('id', $id)
                     ->where('type', 'seller')
                     ->firstOrFail();

        $products = Product::where('seller_id', $id)
                          ->with(['category', 'images', 'primaryImage', 'seller'])
                          ->latest()
                          ->paginate(20);

        $stats = [
            'total_products' => $products->total(),
            'active_products' => Product::where('seller_id', $id)->where('status', 'active')->count(),
        ];

        return view('admin.sellers.products', compact('seller', 'products', 'stats'));
    }


    // app/Http/Controllers/Admin/ProductController.php
   public function toggleSeller(Request $request, $id)
{
    $seller = User::where('id', $id)->where('type', 'seller')->firstOrFail();
    
    // 🔥 TOGGLE SELLER STATUS FIRST (triggers Observer if exists)
    $newSellerStatus = !$seller->is_active;
    $seller->update(['is_active' => $newSellerStatus]);
    
    // 🔥 Force bulk update ALL products (PostgreSQL-safe)
    $newVisibility = $newSellerStatus ? 'true' : 'false';  // String for varchar or casts to boolean
    
    $affected = Product::where('seller_id', $seller->id)
                      ->update(['is_visible' => $newVisibility]);
    
    // 🔥 Debug: Log affected rows
    \Log::info("Toggle seller {$id}: affected products = {$affected}, new status = " . ($newSellerStatus ? 'active' : 'inactive'));
    
    return back()->with('success', 
        $newSellerStatus 
            ? "Seller activated! {$affected} products visible." 
            : "Seller deactivated! {$affected} products hidden."
    );
}




    
}
