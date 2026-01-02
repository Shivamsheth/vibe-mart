<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('home');
    }
    public function profile(){
        return view('customer.profile');
    }
    public function orders(){
        return view('customer.orders');
    }
    public function cart(){


        return view('customer.cart');
    }
    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('customer.cart')->with('success', 'Cart cleared!');
    }

    public function support(){
        return view('customer.support');
    }

    public function wishlist(){
        $wishlist = Wishlist::with('product.images')
        ->where('user_id', Auth::id())
        ->get();
        return view('customer.wishlist', compact('wishlist'));
    }
}
