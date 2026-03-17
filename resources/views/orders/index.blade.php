@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Your Orders</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->count() > 0)
        @foreach ($orders as $order)
            <div class="card mb-4">
                <div class="card-header">
                    Order #{{ $order->id }} - Total: ₹{{ $order->total }}
                </div>
                <div class="card-body">
                    @foreach ($order->items as $item)
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <img src="{{  $item->product->image_url }}" class="img-fluid" alt="{{ $item->product->name }}">
                            </div>
                            <div class="col-md-10">
                                <h5>{{ $item->product->name }}</h5>
                                <h5>Payment Status:
                                <p class="
                                    @if ($order->status === 1)
                                        bg-success
                                    @elseif ($order->status === 0)
                                        bg-warning
                                    @else
                                        bg-danger
                                    @endif
                                ">
                                    {{ $order->status === 1 ? 'SUCCESS' : ($order->status === 0 ? 'PENDING' : 'FAILED') }}
                                </p>

                            </h5>
                                <p>Price: ₹{{ $item->price }} - Quantity: {{ $item->quantity }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <p>You have no orders.</p>
    @endif
</div>
@endsection
