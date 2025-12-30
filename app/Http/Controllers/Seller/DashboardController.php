<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;


class DashboardController extends Controller
{
    public function index()
    {
        $sellerId = Auth::id();
        
        $stats = [
            'total_products' => Product::where('seller_id', $sellerId)->count(),
            'active_products' => Product::where('seller_id', $sellerId)
                                       ->where('status', 'active')
                                       ->count(),
            'pending_products' => Product::where('seller_id', $sellerId)
                                        ->where('status', 'pending')
                                        ->count(),
            'inactive_products' => Product::where('seller_id', $sellerId)
                                         ->where('status', 'inactive')
                                         ->count(),
            'total_stock' => Product::where('seller_id', $sellerId)
                                   ->where('manage_stock', true)
                                   ->sum('stock_quantity'),
            'low_stock' => Product::where('seller_id', $sellerId)
                                 ->where('manage_stock', true)
                                 ->whereColumn('stock_quantity', '<=', 'stock_alert')
                                 ->count()
        ];

        $recentProducts = Product::where('seller_id', $sellerId)
            ->with(['category', 'images'])
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact('stats', 'recentProducts'));
    }

}

