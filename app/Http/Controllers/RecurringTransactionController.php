<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\RecurringTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RecurringTransactionController extends Controller
{
    public function index()
    {
        $recurringTransactions = RecurringTransaction::with(['category', 'account', 'toAccount'])
            ->orderBy('is_active', 'desc')
            ->orderBy('next_occurrence', 'asc')
            ->get();

        return view('recurring-transactions.index', compact('recurringTransactions'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'expense');

        $accounts = Account::active()->get();
        $categories = Category::active()
            ->where('type', $type === 'transfer' ? 'expense' : $type)
            ->get();

        return view('recurring-transactions.create', compact('type', 'accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:expense,income,transfer',
            'amount' => 'required|numeric|min:0.01',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'to_account_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string|max:500',
        ]);

        // Calculate next occurrence
        $validated['next_occurrence'] = $this->calculateNextOccurrence($validated['start_date'], $validated['frequency']);
        $validated['is_active'] = true;

        RecurringTransaction::create($validated);

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Recurring transaction created successfully!');
    }

    public function edit(RecurringTransaction $recurringTransaction)
    {
        $accounts = Account::active()->get();
        $categories = Category::active()
            ->where('type', $recurringTransaction->type === 'transfer' ? 'expense' : $recurringTransaction->type)
            ->get();

        return view('recurring-transactions.edit', compact('recurringTransaction', 'accounts', 'categories'));
    }

    public function update(Request $request, RecurringTransaction $recurringTransaction)
    {
        $validated = $request->validate([
            'type' => 'required|in:expense,income,transfer',
            'amount' => 'required|numeric|min:0.01',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'to_account_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $recurringTransaction->update($validated);

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Recurring transaction updated successfully!');
    }

    public function destroy(RecurringTransaction $recurringTransaction)
    {
        $recurringTransaction->delete();

        return back()->with('success', 'Recurring transaction deleted successfully!');
    }

    public function toggleStatus(RecurringTransaction $recurringTransaction)
    {
        $recurringTransaction->update([
            'is_active' => !$recurringTransaction->is_active
        ]);

        $status = $recurringTransaction->is_active ? 'activated' : 'paused';
        return back()->with('success', "Recurring transaction {$status} successfully!");
    }

    private function calculateNextOccurrence($startDate, $frequency)
    {
        $start = Carbon::parse($startDate);
        $now = Carbon::now();

        if ($start->greaterThan($now)) {
            return $start;
        }

        switch ($frequency) {
            case 'daily':
                return $now->addDay()->startOfDay();
            case 'weekly':
                return $now->addWeek()->startOfDay();
            case 'monthly':
                return $now->addMonth()->startOfDay();
            case 'yearly':
                return $now->addYear()->startOfDay();
            default:
                return $now->addDay()->startOfDay();
        }
    }
}
