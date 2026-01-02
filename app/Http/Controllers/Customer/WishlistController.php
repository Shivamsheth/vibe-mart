<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
 

class WishlistController extends Controller
{
    /**
     * Show wishlist page
     */
//     public function index()
// {
//     $wishlist = Wishlist::with('product.images')
//         ->where('user_id', Auth::id())
//         ->get();

//        Log::info(['wishlist'=>$wishlist]);

        
//     return view('customer.wishlist', compact('wishlist'));
// }


    /**
     * Add / Remove wishlist (Toggle)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId    = Auth::id();
        $productId = (int) $request->product_id;

        // Check if already exists
        $wishlist = Wishlist::where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->first();

        // 🔁 REMOVE
        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'success' => true,
                'action'  => 'removed',
                'message' => 'Removed from wishlist'
            ]);
        }

        // 🔥 ADD
        $product = Product::findOrFail($productId);

        if (!$product->is_visible) {
            return response()->json([
                'success' => false,
                'error'   => 'Product unavailable'
            ], 422);
        }

        Wishlist::create([
            'user_id'    => $userId,
            'product_id' => $productId,
        ]);

        return response()->json([
            'success' => true,
            'action'  => 'added',
            'message' => 'Added to wishlist'
        ]);
    }
}
