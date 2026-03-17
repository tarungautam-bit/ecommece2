@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">My Wallet</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Current Wallet Amount</h5>
            <p class="card-text">₹{{ number_format($totalAmount, 2) }}</p>
        </div>
    </div>

    <h2 class="my-4">Transaction History</h2>

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

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>₹{{ number_format($transaction->amount, 2) }}</td>
                    <td>
                        @if($transaction->type == 'admin_credit')
                            Campaign Credit
                        @elseif($transaction->type == 'admin_debit')
                            Campaign Debit
                        @elseif($transaction->type == 'order_credit')
                            Order Credit
                        @elseif($transaction->type == 'order_debit')
                            Order Debit
                        @else
                            {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                        @endif
                    </td>
                    <td>{{ ucfirst($transaction->status) }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection