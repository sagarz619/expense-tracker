<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total balance across all active accounts
        $totalBalance = Account::active()->sum('current_balance');

        // Get all active accounts
        $accounts = Account::active()->get();

        // Get current month stats
        $monthlyIncome = Transaction::income()->thisMonth()->sum('amount');
        $monthlyExpense = Transaction::expense()->thisMonth()->sum('amount');

        // Get recent transactions (last 7)
        $recentTransactions = Transaction::with(['category', 'account'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get();

        return view('dashboard', compact(
            'totalBalance',
            'accounts',
            'monthlyIncome',
            'monthlyExpense',
            'recentTransactions'
        ));
    }
}
