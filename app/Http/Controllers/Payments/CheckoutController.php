<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Responses;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    use Responses;
    
        /**
     * 🔥 Get cart summary (optional - for mini-cart)
     */
    public function orderSummary(Request $request)
    {
        return view('payment.checkout');
       
    }

   

    public function orderCreation(Request $request)
    {
        try {

            // 1️⃣ Base validation
            $validated = $request->validate([
                'name'             => 'required|string|max:100',
                'address'          => 'required|string|max:255',
                'phone'            => 'required|string|min:10|max:15',
                'email'            => 'required|email',
                'alternate_number' => 'nullable|string|min:10|max:15',
                'payment_method'   => 'required|in:cash,card,upi',
            ]);

            // 2️⃣ Business rule
            if ($validated['payment_method'] === 'cash') {
                return $this->invalidPaymentMethod(); // 403
            }

            // 3️⃣ Card validation only if needed
            $card = null;

            if ($validated['payment_method'] === 'card') {
                $card = $request->validate([
                    'card_number' => 'required|digits_between:12,16',
                    'expiry'      => 'required|string',
                    'cvv'         => 'required|digits:3',
                ]);
            }

            // 4️⃣ Create Order
            $order = Order::create([
                'order_id'        => uniqid('ORD-'),
                'customer_id'     => auth()->id() ?? 1, // temporary fallback
                'method_payment'  => $validated['payment_method'],
                'payment_status'  => 'pending',
                
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Order created successfully',
                'data'    => [
                    'order' => $order,
                    'card'  => $card
                ]
            ], 201);

        } catch (ValidationException $e) {

            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    

      
    
}
