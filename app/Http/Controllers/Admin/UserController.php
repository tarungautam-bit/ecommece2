<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;

class UserController extends Controller
{
    public function index()
    {
        // Retrieve only users of type 'user'
        $users = User::where('type', 'user')->get();
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'type' => 'required|string|in:user,admin'
        ]);

        $user->update($request->only('name', 'email', 'type'));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }


    public function credit(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        $user->total_amount += $request->amount;
        $user->save();

        WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'type' => 'admin_credit',
            'status' => 'completed'
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Amount credited successfully.');
    }

 

    public function debit(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        if ($user->total_amount < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient balance.');
        }

        $user->total_amount -= $request->amount;
        $user->save();

        WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'type' => 'admin_debit',
            'status' => 'completed'
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Amount debited successfully.');
    }

}
