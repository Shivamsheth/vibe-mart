<?php
// app/Http/Controllers/Cart/CartController.php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * 🔥 Add item to cart (AJAX from home page)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'sometimes|integer|min:1|max:99'
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->integer('quantity', 1);

        // Fetch product with images
        $product = Product::with('images')->findOrFail($productId);

        // Stock & visibility check
        if (!$product->is_visible || ($product->stock_quantity ?? 999) < $quantity) {
            return response()->json([
                'success' => false,
                'error' => 'Product unavailable or insufficient stock'
            ], 422);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            // Increase existing quantity
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // New item
            $price = $product->sale_price ?? $product->price;
            $image = $product->images->first() 
                ? Storage::url($product->images->first()->path) 
                : asset('images/no-image.jpg');

            $cart[$productId] = [
                'id' => (int) $product->id,
                'name' => $product->name,
                'price' => (float) $price,
                'image' => $image,
                'quantity' => $quantity
            ];
        }

        // Save to session
        session(['cart' => $cart]);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        session(['cart_count' => $cartCount]);

        // 🔥 LIVE COUNTER SUPPORT
        return response()->json([
            'success' => true,
            'message' => 'Added to cart successfully!',
            'cart_count' => $cartCount,
            'cart' => $cart  // 👈 Full cart for live sync
        ]);
    }

    /**
     * 🔥 Show cart page
     */
    public function show()
    {
        $cart = session('cart', []);
        $totalItems = array_sum(array_column($cart, 'quantity'));
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }

        return view('customer.cart', compact('cart', 'totalItems', 'subtotal'));
    }

    /**
     * 🔥 Update cart item quantity (AJAX)
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return response()->json([
                'success' => false,
                'error' => 'Item not found in cart'
            ], 404);
        }

        // Update quantity
        $cart[$productId]['quantity'] = $quantity;
        
        // Remove if quantity is 0
        if ($quantity <= 0) {
            unset($cart[$productId]);
        }

        session(['cart' => $cart]);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        session(['cart_count' => $cartCount]);

        // 🔥 LIVE COUNTER SUPPORT
        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
            'cart' => $cart  // 👈 Full cart for live sync
        ]);
    }

    /**
     * 🔥 Remove single item (AJAX + Form)
     */
    public function remove(Request $request, $productId)
    {
        $cart = session('cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['cart' => $cart]);
            
            $cartCount = array_sum(array_column($cart, 'quantity'));
            session(['cart_count' => $cartCount]);

            // 🔥 LIVE COUNTER SUPPORT - Check if AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed successfully!',
                    'cart_count' => $cartCount,
                    'cart' => $cart  // 👈 Full cart for live sync
                ]);
            }
            
            return back()->with('success', 'Item removed from cart!');
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'error' => 'Item not found in cart'
            ], 404);
        }
        
        return back()->with('error', 'Item not found in cart.');
    }

    /**
     * 🔥 Clear entire cart (AJAX + Form)
     */
    public function clear(Request $request)
    {
        // Verify CSRF
        if (!$request->has('_token')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid request'
                ], 422);
            }
            return back()->with('error', 'Invalid request');
        }
        
        // Clear cart
        session()->forget(['cart', 'cart_count']);
        
        // 🔥 LIVE COUNTER SUPPORT
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '🛒 Cart cleared successfully!',
                'cart_count' => 0,
                'cart' => []  // 👈 Empty cart for live sync
            ]);
        }
        
        return redirect()->route('cart.show')
            ->with('success', '🛒 Cart cleared successfully! Start shopping again.');
    }

    /**
     * 🔥 Get cart summary (for navbar/mini-cart)
     */
    public function summary()
    {
        $cart = session('cart', []);
        $totalItems = array_sum(array_column($cart, 'quantity'));
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }

        return response()->json([
            'count' => $totalItems,
            'subtotal' => number_format($subtotal, 0),
            'cart' => $cart  // 👈 Full cart
        ]);
    }

    /**
     * 🔥 Get live cart count only (lightweight)
     */
    public function count()
    {
        $cart = session('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        
        return response()->json([
            'count' => $count
        ]);
    }
}
