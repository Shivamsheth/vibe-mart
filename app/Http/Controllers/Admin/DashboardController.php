<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;




class DashboardController extends Controller
{
    public function index(Request $request)
    {
       // $userId = Auth::id();
       // dd($userId);

        $stats = [
            'active_customers' => User::where('type','customer')
            ->where('is_active','true')
            ->count(),

            'low_stock' => Product::whereColumn('stock_quantity', '<' , 'stock_alert')->count(),


        ];


        return view('admin.dashboard',compact('stats'));
    }
    // public function products(){
    //     return view('admin.products.index')
    // } 
}
