<?php
// app/Http/Controllers/CheckoutController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        return view('checkout.address', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required',
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

        // Generate Razorpay Order
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $razorpayOrder = $api->order->create([
            'receipt'         => (string) $order->id,
            'amount'          => (string) ($total * 100),
            'currency'        => 'INR',
            'payment_capture' => 1,
        ]);
        
        return view('checkout.payment', [
            'orderId' => $order->id,
            'amount' => $total * 100, // Amount in paise
            'razorpayOrderId' => $razorpayOrder->id,
        ]);
    }

    public function paymentCallback(Request $request)
    {
       // dd($request);
        $paymentId = $request->input('razorpay_payment_id');
        $orderId = $request->input('orderid');
        $signature = $request->input('razorpay_signature');

        //dd($orderId);

        $order = Order::findOrFail($orderId);

        // Verify the payment signature
        $keySecret = config('services.razorpay.secret');
        //dd( $keySecret);
        $generatedSignature = hash_hmac('sha256', $request->input('razorpay_order_id') . '|' . $request->input('razorpay_payment_id'), $keySecret);

        if ($signature === $signature) {
            $order->status = 1; // Payment success
            $order->save();
            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
        } else {
            $order->status = 2; // Payment failed
            $order->save();
            return redirect()->route('orders.index');
        }
    }
}
