@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Order Details</h1>
    <p><strong>Order ID:</strong> {{ $order->id }}</p>
    <p><strong>User:</strong> {{ $order->user->name }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <!-- Add more details as needed -->
</div>
@endsection
