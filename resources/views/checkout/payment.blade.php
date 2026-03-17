@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Payment</h1>

    <!-- Button to trigger Razorpay payment -->
    <button id="pay-button" class="btn btn-primary">Pay Now</button>

    <form id="razorpay-form" action="{{ route('checkout.paymentCallback') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input  name="orderid" id="orderid" value="{{$orderId}}">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>

    <!-- Load Razorpay checkout script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "{{ config('services.razorpay.key') }}", // Enter the Key ID generated from the Dashboard
            "amount": "{{ $amount }}", // Amount in the smallest currency unit (e.g., paise)
            "currency": "INR",
            "order_id": "{{ $razorpayOrderId }}", // Order ID generated from Razorpay
            "name": "Training Ecommerce",
            "description": "Payment for Order #{{ $orderId }}",
            "image": "{{ asset('path/to/your/logo.png') }}",
            "handler": function (response){
                // Handle the response from Razorpay here
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('razorpay-form').submit();
            },
            "prefill": {
                "name": "{{ Auth::user()->name }}",
                "email": "{{ Auth::user()->email }}"
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        document.getElementById('pay-button').onclick = function (e) {
            e.preventDefault();
            var rzp1 = new Razorpay(options);
            rzp1.open();
        };
    </script>
</div>
@endsection
