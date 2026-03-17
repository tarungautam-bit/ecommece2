<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        return view('cart.index', compact('cartItems'));
    }

    public function add(Product $product)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
        } else {
            $cartItem = new Cart;
            $cartItem->user_id = Auth::id();
            $cartItem->product_id = $product->id;
            $cartItem->quantity = 1;
        }

        $cartItem->save();
        return redirect()->route('cart.index');
    }

    public function remove(Product $product)
    {
       
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            if ($cartItem->quantity > 1) {
                $cartItem->quantity -= 1;
                $cartItem->save();
            } else {
                $cartItem->delete();
            }
        }

        return redirect()->route('cart.index');
    }
}
