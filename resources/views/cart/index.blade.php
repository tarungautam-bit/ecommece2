@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Your Cart</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($cartItems->count() > 0)
        <div class="row">
            @foreach ($cartItems as $cartItem)
                <div class="col-md-4 mb-4">
                    <div class="card">
                    `<img src="{{ $cartItem->product->image_url }}" class="card-img-top" alt="{{ $cartItem->product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $cartItem->product->name }}</h5>
                            <p class="card-text">{{ $cartItem->product->description }}</p>
                            <p class="card-text">Price: ₹{{ $cartItem->product->price }}</p>
                            <p class="card-text">Quantity: {{ $cartItem->quantity }}</p>
                            <form action="{{ route('cart.remove', $cartItem->product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <form action="{{ route('checkout.index') }}" method="GET">
                @csrf
                <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
            </form>
        </div>
    @else
        <p>Your cart is empty.</p>
    @endif
</div>
@endsection
