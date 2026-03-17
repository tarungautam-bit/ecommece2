<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cashback;
use App\Http\Requests\SetCashbackRequest;
use Illuminate\Http\Request;

class CashbackController extends Controller
{
    public function index()
    {
        $cashbacks = Cashback::all();
        return view('admin.cashback.index', compact('cashbacks'));
    }

    public function create()
    {
        return view('admin.cashback.create');
    }

    public function store(SetCashbackRequest $request)
    {
        $validated = $request->validated();

        // Check if an active cashback of the same type already exists
        $existingCashback = Cashback::where('type', $validated['type'])
                                    ->where('status', 1)
                                    ->first();

        if ($existingCashback) {
            return redirect()->back()->with('error', 'An active cashback of this type already exists.');
        }

        $cashback = new Cashback();
        $cashback->percentage = $validated['percentage'];
        $cashback->type = $validated['type'];
        $cashback->status = $validated['status'];
        $cashback->save();

        return redirect()->route('admin.cashback.index')->with('success', 'Cashback set successfully.');
    }

    public function edit(Cashback $cashback)
    {
        return view('admin.cashback.edit', compact('cashback'));
    }

    public function update(SetCashbackRequest $request, Cashback $cashback)
    {
        $validated = $request->validated();

        // Check if an active cashback of the same type already exists
        $existingCashback = Cashback::where('type', $validated['type'])
                                    ->where('status', 1)
                                    ->where('id', '!=', $cashback->id)
                                    ->first();

        if ($existingCashback) {
            return redirect()->back()->with('error', 'An active cashback of this type already exists.');
        }

        $cashback->update($validated);
        return redirect()->route('admin.cashback.index')->with('success', 'Cashback updated successfully.');
    }

    public function destroy(Cashback $cashback)
    {
        $cashback->delete();
        return redirect()->route('admin.cashback.index')->with('success', 'Cashback deleted successfully.');
    }
}