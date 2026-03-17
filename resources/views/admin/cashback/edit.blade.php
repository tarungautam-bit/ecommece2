@extends('layouts.admin')

@section('content')
    <h1>Edit Cashback</h1>
       
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
    <form action="{{ route('admin.cashback.update', $cashback->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="percentage">Percentage</label>
            <input type="number" name="percentage" id="percentage" class="form-control" value="{{ $cashback->percentage }}" required min="0" max="100">
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="wallet" {{ $cashback->type == 'wallet' ? 'selected' : '' }}>Wallet</option>
                <option value="cart" {{ $cashback->type == 'cart' ? 'selected' : '' }}>Cart</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="1" {{ $cashback->status == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $cashback->status == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection