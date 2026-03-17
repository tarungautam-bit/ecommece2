@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Manage Orders</h1>

    @if($orders->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Address</th>
              
                    <th>Total Amount</th>
                    <th>Payment Status</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td>{{ $order->address }}</td>
                        
                        <td>₹{{ $order->total }}</td>
                        <td>{{ $order->status_label }}</td>

                        <td>
                            <ul class="list-unstyled">
                                @foreach ($order->items as $item)
                                    <li class="mb-2">
                                        @if($item->product)
                                            <img src="{{ asset('storage/'.$item->product->image) }}"
                                                 alt="{{ $item->product->name }}"
                                                 style="width: 50px; height: auto;">

                                            <strong>{{ $item->product->name }}</strong> <br>
                                            Quantity: {{ $item->quantity }} <br>
                                            Price: ₹{{ $item->price }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination Links --}}
        <div class="mt-3">
            {{ $orders->links() }}
        </div>

    @else
        <p>No orders found.</p>
    @endif
</div>
@endsection
