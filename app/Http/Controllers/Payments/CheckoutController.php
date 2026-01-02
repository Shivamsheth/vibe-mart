<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    /**
     * STEP 1: LOCK CHECKOUT (AJAX)
     */
    public function orderSummary(Request $request)
    {
        $userId = Auth::id(); // ✅ FIXED

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $cart = session('cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 422);
        }

        // 🔒 Recalculate total
        $total = collect($cart)->sum(fn ($item) =>
            $item['price'] * $item['quantity']
        );

        session([
            'checkout' => [
                'user_id' => $userId,
                'cart'    => $cart,
                'total'   => $total
            ]
        ]);

        return response()->json([
            'success'  => true,
            'redirect' => route('checkout.payment') 

        ]);
    }

    /**
     * STEP 2: CREATE ORDER
     */
    public function orderCreation(Request $request)
    {
        try {
            $checkout = session('checkout');

            if (!$checkout || $checkout['user_id'] !== auth()->id()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid checkout session'
                ], 403);
            }

            // ✅ Validation
            $validated = $request->validate([
                'name'             => 'required|string|max:100',
                'address'          => 'required|string|max:255',
                'phone'            => 'required|string|min:10|max:15',
                'email'            => 'required|email',
                'alternate_number' => 'nullable|string|min:10|max:15',
                'payment_method'   => 'required|in:cash,card,upi',
            ]);

            if ($validated['payment_method'] === 'cash') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Cash payment is not allowed'
                ], 403);
            }

            if ($validated['payment_method'] === 'card') {
                $request->validate([
                    'card_number' => 'required|digits_between:12,16',
                    'expiry'      => 'required|string',
                    'cvv'         => 'required|digits:3',
                ]);
            }

            DB::beginTransaction();

            $order = Order::create([
                'order_id'       => uniqid('ORD-'),
                'customer_id'    => auth()->id(),
                'method_payment' => $validated['payment_method'],
                'payment_status' => 'pending',
                'total_amount'   => $checkout['total'],
                'address'        => $validated['address'],
                'phone'          => $validated['phone'],
                'email'          => $validated['email'],
            ]);

            foreach ($checkout['cart'] as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id'=> $item['id'],
                    'price'     => $item['price'],
                    'quantity'  => $item['quantity'],
                ]);
            }

            DB::commit();

            session()->forget(['cart', 'checkout']);

            return response()->json([
                'status'  => 'success',
                'message' => 'Order created successfully',
                'data'    => [
                    'order_id' => $order->order_id
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Server error',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    public function paymentPage()
    {
        $checkout = session('checkout');

        if (!$checkout || $checkout['user_id'] !== auth()->id()) {
            return redirect()
                ->route('cart.show')
                ->with('error', 'Invalid checkout session');
        }

        return view('payment.checkout', [
            'cart'  => $checkout['cart'],
            'total' => $checkout['total'],
        ]);
    }


}
