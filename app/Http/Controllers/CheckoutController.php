<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\WalletTransaction;
use App\Models\Cashback;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();
        $walletBalance = Auth::user()->total_amount;

        // Get the active cashback percentage
        $cashback = Cashback::where('status', 1)->first();
        $cashbackPercentage = $cashback ? $cashback->percentage : 0;

        return view('checkout.billing', compact('cartItems', 'walletBalance', 'cashbackPercentage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'use_wallet' => 'boolean',
        ]);
    
        $cartItems = Cart::where('user_id', Auth::id())
            ->leftJoin('products', 'carts.product_id', '=', 'products.id')
            ->select('carts.*', 'products.name as product_name', 'products.description as product_description', 'products.price as product_price')
            ->get();
    
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
    
        $total = $cartItems->sum(function ($cartItem) {
            return $cartItem->product_price * $cartItem->quantity;
        });
    
        $walletBalance = Auth::user()->total_amount;
        $useWallet = $request->input('use_wallet', false);
        $walletUsed = 0;
    
        $cashback = Cashback::where('status', 1)->first();
        $cashbackPercentage = $cashback ? $cashback->percentage : 0;
    
        if ($useWallet && $walletBalance > 0) {
            if ($total < 50) {
                return redirect()->route('checkout.index')->with('error', 'Minimum order amount to apply wallet credits is 50.');
            }
    
            $maxWalletUsage = ($total * $cashbackPercentage) / 100;
    
            if ($walletBalance >= $maxWalletUsage) {
                $walletUsed = $maxWalletUsage;
                $total -= $maxWalletUsage;
            } else {
                $walletUsed = $walletBalance;
                $total -= $walletBalance;
            }
    
            // Deduct wallet balance
            $user = Auth::user();
            $user->total_amount -= $walletUsed;
            $user->save();
    
            // Create wallet transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $walletUsed,
                'type' => 'order_debit',
                'status' => 'completed'
            ]);
        }
    
        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'address' => $request->input('address'),
            'status' => 0, // Pending
        ]);
    
        // Create order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product_price,
            ]);
        }

        // Clear cart
        Cart::where('user_id', Auth::id())->delete();
    
        if ($total > 0) {
            // Generate Razorpay Order
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $razorpayOrder = $api->order->create([
                'receipt'         => (string) $order->id,
                'amount'          => (int) ($total * 100),
                'currency'        => 'INR',
                'payment_capture' => 1,
            ]);
            
            return view('checkout.payment', [
                'orderId' => $order->id,
                'amount' => $total * 100, // Amount in paise
                'razorpayOrderId' => $razorpayOrder->id,
            ]);
        } else {
            $order->status = 1; // Payment success
            $order->save();
            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
        }
    }

    public function paymentCallback(Request $request)
    {
        $paymentId = $request->input('razorpay_payment_id');
        $orderId = $request->input('orderid');
        $signature = $request->input('razorpay_signature');

        $order = Order::findOrFail($orderId);

        // Verify the payment signature
        $keySecret = config('services.razorpay.secret');
        $generatedSignature = hash_hmac('sha256', $request->input('razorpay_order_id') . '|' . $request->input('razorpay_payment_id'), $keySecret);

        if ($signature === $generatedSignature) {
            $order->status = 1; // Payment success
            $order->save();
            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
        } else {
            $order->status = 2; // Payment failed
            $order->save();
            return redirect()->route('orders.index')->with('error', 'Payment failed!');
        }
    }
}