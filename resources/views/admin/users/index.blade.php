@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Manage Users</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Add User</a>

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
                <th>Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Total Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->type }}</td>
                    <td>{{ $user->total_amount }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#creditModal" data-user-id="{{ $user->id }}">Credit</button>
                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#debitModal" data-user-id="{{ $user->id }}">Debit</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Credit Modal -->
<div class="modal fade" id="creditModal" tabindex="-1" role="dialog" aria-labelledby="creditModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="creditForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="creditModalLabel">Credit Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="credit_amount">Amount</label>
                        <input type="number" name="amount" id="credit_amount" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Credit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Debit Modal -->
<div class="modal fade" id="debitModal" tabindex="-1" role="dialog" aria-labelledby="debitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="debitForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="debitModalLabel">Debit Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="debit_amount">Amount</label>
                        <input type="number" name="amount" id="debit_amount" class="form-control" required >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Debit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $('#creditModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        var form = $('#creditForm');
        form.attr('action', '/admin/users/' + userId + '/credit');
    });

    $('#debitModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        var form = $('#debitForm');
        form.attr('action', '/admin/users/' + userId + '/debit');
    });
</script>
@endsection