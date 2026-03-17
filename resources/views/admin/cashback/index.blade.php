@extends('layouts.admin')

@section('content')
    <h1>Cashback List</h1>
    <a href="{{ route('admin.cashback.create') }}" class="btn btn-primary">Add Cashback</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Percentage</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cashbacks as $cashback)
                <tr>
                    <td>{{ $cashback->id }}</td>
                    <td>{{ $cashback->percentage }}</td>
                    <td>{{ $cashback->type }}</td>
                    <td>{{ $cashback->cashback_status }}</td>
                    <td>
                        <a href="{{ route('admin.cashback.edit', $cashback->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('admin.cashback.destroy', $cashback->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection