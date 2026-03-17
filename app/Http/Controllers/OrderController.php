<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->get();
        return view('orders.index', compact('orders'));
    }

    public function store()
    {
        $cartItems = DB::table('carts')
            ->leftJoin('products', 'carts.product_id', '=', 'products.id')
            ->where('carts.user_id', Auth::id())
            ->select('carts.*', 'products.name as product_name', 'products.description as product_description', 'products.price as product_price')
            ->get();
    
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
    
        $total = $cartItems->sum(function ($cartItem) {
            return $cartItem->product_price * $cartItem->quantity;
        });
    
        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
        ]);
    
        // Add order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product_price,
            ]);
        }
    
        // Clear the cart
        Cart::where('user_id', Auth::id())->delete();
    
        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }
    
}
