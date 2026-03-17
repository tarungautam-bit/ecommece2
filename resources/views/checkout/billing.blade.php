@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Billing Information</h1>

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
            <div class="form-group">
                <label for="use_wallet">
                    <input type="checkbox" id="use_wallet" name="use_wallet" value="1">
                    Use Wallet Balance (Up to {{ $cashbackPercentage }}% of the total amount)
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Apply Wallet Credits</button>
        </form>
    @else
        <p>Your cart is empty.</p>
    @endif
</div>
@endsection