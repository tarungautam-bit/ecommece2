<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletTransactionController extends Controller
{
    public function index()
    {
        $transactions = WalletTransaction::with('user')->get();
        return view('admin.wallet_transactions.index', compact('transactions'));
    }

    public function destroy(WalletTransaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('admin.wallet_transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}