<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Order;

class RazorpayController extends Controller
{
    public function paymentCallback(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $paymentId = $request->input('razorpay_payment_id');
        $orderId = $request->input('razorpay_order_id');
        $signature = $request->input('razorpay_signature');

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ]);

            // Update the order status to 'completed'
            $order = Order::where('id', $orderId)->first();
            $order->status = 'completed';
            $order->save();

            return redirect()->route('order.success')->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            // Update the order status to 'failed'
            $order = Order::where('id', $orderId)->first();
            $order->status = 'failed';
            $order->save();

            return redirect()->route('order.failure')->with('error', 'Payment failed!');
        }
    }
}
