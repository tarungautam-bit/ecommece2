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
        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
        </form>
    @else
        <p>Your cart is empty.</p>
    @endif
</div>
@endsection
