<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['category', 'account', 'toAccount']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by account
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('date', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(20);

        $accounts = Account::active()->get();
        $categories = Category::active()->get();

        return view('transactions.index', compact('transactions', 'accounts', 'categories'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'expense');

        $accounts = Account::active()->get();
        $categories = Category::active()
            ->where('type', $type === 'transfer' ? 'expense' : $type)
            ->get();

        return view('transactions.create', compact('type', 'accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:expense,income,transfer',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'to_account_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create($validated);

            // Update account balances
            $this->updateAccountBalances($transaction);

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', ucfirst($transaction->type) . ' added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to add transaction: ' . $e->getMessage());
        }
    }

    public function edit(Transaction $transaction)
    {
        $accounts = Account::active()->get();
        $categories = Category::active()
            ->where('type', $transaction->type === 'transfer' ? 'expense' : $transaction->type)
            ->get();

        return view('transactions.edit', compact('transaction', 'accounts', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'type' => 'required|in:expense,income,transfer',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'to_account_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Reverse old transaction balances
            $this->reverseAccountBalances($transaction);

            // Update transaction
            $transaction->update($validated);

            // Apply new transaction balances
            $this->updateAccountBalances($transaction);

            DB::commit();

            return redirect()->route('transactions.index')
                ->with('success', 'Transaction updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update transaction: ' . $e->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            // Reverse account balances
            $this->reverseAccountBalances($transaction);

            // Delete transaction
            $transaction->delete();

            DB::commit();

            return back()->with('success', 'Transaction deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete transaction: ' . $e->getMessage());
        }
    }

    /**
     * Update account balances based on transaction
     */
    private function updateAccountBalances(Transaction $transaction)
    {
        $account = Account::find($transaction->account_id);

        switch ($transaction->type) {
            case 'expense':
                $account->decrement('current_balance', $transaction->amount);
                break;

            case 'income':
                $account->increment('current_balance', $transaction->amount);
                break;

            case 'transfer':
                $account->decrement('current_balance', $transaction->amount);
                $toAccount = Account::find($transaction->to_account_id);
                $toAccount->increment('current_balance', $transaction->amount);
                break;
        }
    }

    /**
     * Reverse account balances (for update/delete)
     */
    private function reverseAccountBalances(Transaction $transaction)
    {
        $account = Account::find($transaction->account_id);

        switch ($transaction->type) {
            case 'expense':
                $account->increment('current_balance', $transaction->amount);
                break;

            case 'income':
                $account->decrement('current_balance', $transaction->amount);
                break;

            case 'transfer':
                $account->increment('current_balance', $transaction->amount);
                $toAccount = Account::find($transaction->to_account_id);
                $toAccount->decrement('current_balance', $transaction->amount);
                break;
        }
    }

    public function export(Request $request)
    {
        $query = Transaction::query();

        // Apply same filters as index
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');

        $filename = 'transactions_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new TransactionsExport($query), $filename);
    }
}
