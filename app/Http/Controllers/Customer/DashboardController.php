<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

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
        return view('customer.wishlist');
    }
}
