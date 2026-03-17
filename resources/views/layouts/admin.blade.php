<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Admin Panel') }}</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>   
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            padding: 1rem;
        }
        .sidebar a {
            color: #fff;
            display: block;
            padding: 0.5rem 0;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            flex: 1;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <nav class="sidebar">
            <h2 class="text-white">Admin Panel</h2>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.products.index') }}">Manage Products</a>
            <a href="{{ route('admin.orders.index') }}">Manage Orders</a>
            <a href="{{ route('admin.users.index') }}">Manage Users</a>
            <a href="{{ route('admin.cashback.index') }}">Manage Cashback</a>
            <a href="{{ route('admin.wallet_transactions.index') }}">Manage Wallet Transactions</a>
            <a href="{{ route('admin.logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
        <div class="content">
            @yield('content')
        </div>
    </div>

</body>
</html>