<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class WalletTransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = WalletTransaction::where('user_id', $user->id)->get();
        $totalAmount = $user->total_amount;

        return view('wallet.index', compact('transactions', 'totalAmount'));
    }
}