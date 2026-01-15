<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::orderBy('is_active', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,card',
            'opening_balance' => 'required|numeric',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        $validated['current_balance'] = $validated['opening_balance'];
        $validated['is_active'] = true;

        Account::create($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully!');
    }

    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,card',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $account->update($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Account updated successfully!');
    }

    public function destroy(Account $account)
    {
        // Check if account has transactions
        if ($account->transactions()->count() > 0) {
            return back()->with('error', 'Cannot delete account with existing transactions. Deactivate it instead.');
        }

        $account->delete();

        return back()->with('success', 'Account deleted successfully!');
    }

    public function toggleStatus(Account $account)
    {
        $account->update([
            'is_active' => !$account->is_active
        ]);

        $status = $account->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Account {$status} successfully!");
    }
}
